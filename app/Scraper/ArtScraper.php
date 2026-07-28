<?php

namespace App\Scraper;

use App\Models\{Art, ArtCategory, ArtComment, ArtPreview, ArtPreviewCategory, ArtType, File, License, Tag};
use Carbon\Carbon;
use Exception;
use Symfony\Component\DomCrawler\Crawler;

class ArtScraper {
    public function __construct(private AuthScraper $authScraper) {}

    public function update(string $id, string | null $token): Art {
        $crawler = $this->authScraper->authenticate("https://opengameart.org/content/{$id}", $token);
        $art = $this->syncArtRecord($crawler, $id, $token, true);

        $this->removeAllPreviousPreviews($id);
        $this->scrapeAllPreviewsAndStore($crawler, $art['art_category'], $id);

        $this->removeLatestComment($id);
        $this->scrapeLatestCommentAndStore($crawler, $id, $token);

        $this->removeAllPreviousFiles($id);
        $this->scrapeAllFilesAndStore($crawler, $id);

        return $art['art_db']->load([
            'user',
            'art_category',
            'art_previews.art_preview_category',
            'files',
            'tags',
            'art_comments' => fn($query) => $query->with('user')->orderByDesc('created_at')
        ]);
    }

    public function show(string $id, string | null $token): Art {
        $crawler = $this->authScraper->authenticate("https://opengameart.org/content/{$id}", $token);
        $art = $this->syncArtRecord($crawler, $id, $token, false);

        $this->removeAllPreviousPreviews($id);
        $this->scrapeAllPreviewsAndStore($crawler, $art['art_category'], $id);

        $licenses = $this->scrapeLicensesAndStore($crawler);
        $art['art_db']->licenses()->sync(collect($licenses)->pluck('id')->toArray());

        $this->removeAllComments($id);
        $this->scrapeAllCommentsAndStore($crawler, $id, $token);

        $this->removeAllPreviousFiles($id);
        $this->scrapeAllFilesAndStore($crawler, $id);

        return $art['art_db']->load([
            'user',
            'art_category',
            'art_previews.art_preview_category',
            'files',
            'tags' => fn($query) => $query->orderBy('name'),
            'art_comments' => fn($query) => $query->with('user')->orderByDesc('created_at'),
            'licenses' => fn($query) => $query->orderBy('name'),
        ]);
    }

    public function index(array $filters, string | null $token): array {
        $art_types = ArtType::get();
        $art_types_id = $art_types->pluck('id')->toArray();

        $params = [
            'keys' => $filters['search'] ?? null,
            'title' => '',
            'field_art_tags_tid_op' => 'or',
            'field_art_tags_tid' => $filters['field_art_tags_tid'] ?? '',
            'name' => '',
            'field_art_type_tid' => (int) ($filters['field_art_type_tid'] ?? 0) === 0 ? $art_types_id : [(int) $filters['field_art_type_tid']],
            'field_art_licenses_tid' => [2, 10310, 31772, 17981, 6, 5, 4, 17982, 3],
            'sort_by' => 'score',
            'sort_order' => 'DESC',
            'Collection' => '',
        ];

        $page = (int) ($filters['page'] ?? 0);
        if ($page > 1) {
            $params['page'] = $page - 1;
        }

        $query = http_build_query($params);
        $query = preg_replace('/field_art_licenses_tid%5B\d+%5D=/', 'field_art_licenses_tid%5B%5D=', $query);
        $query = preg_replace('/field_art_type_tid%5B\d+%5D=/', 'field_art_type_tid%5B%5D=', $query);

        $url = "https://opengameart.org/art-search-advanced?" . $query;

        $crawler = $this->authScraper->authenticate($url, $token, false);

        $this->scrapeArtTypeAndStore($crawler);

        $total_result = 0;
        if ($crawler->filter('.view-display-id-search_art_advanced .view-header')->count() > 0) {
            $total_raw = $crawler->filter('.view-display-id-search_art_advanced .view-header')->text();
            $total_result = (int) explode('of ', $total_raw)[1];
        }

        $arts = $crawler->filter('.view-display-id-search_art_advanced .view-content .art-previews-inline')->each(function (Crawler $node) {
            $type = 'Art';
            $preview_type = 'image';
            $previews = '';

            $id = str_replace('/content/', '', $node->filter('.view-mode-art_preview .field-name-title .field-items .field-item span a')->attr('href'));

            if (Art::where('id', $id)->exists()) {
                return Art::where('id', $id)->with([
                    'user',
                    'art_category',
                    'art_previews.art_preview_category',
                    'files',
                    'art_comments' => fn($query) => $query->with('user')->orderByDesc('created_at')
                ])->first()->toArray();
            }

            if ($node->filter('.view-mode-art_preview .field-name-field-art-preview .field-items .field-item a img')->count() > 0) {
                $previews = $node->filter('.view-mode-art_preview .field-name-field-art-preview .field-items .field-item a img')->attr('src');
            } else {
                $previews = $node->filter('.audio-preview .play-button')->attr('data-mp3-url');
                $preview_type = 'audio';
                $type = 'Music';
            }

            return [
                'id' => $id,
                'title' => $node->filter('.view-mode-art_preview .field-name-title .field-items .field-item span a')->text(),
                'user' => [
                    'image_url' => env('APP_URL') . '/images/icon.png'
                ],
                'art_category' => [
                    'name' => $type
                ],
                'art_previews' => [
                    [
                        'url' => $previews,
                        'id' => 1,
                        'art_preview_category' => [
                            'name' => $preview_type,
                        ],
                    ]
                ],
                'art_comments' => [],
                'files' => [],
            ];
        });

        return [
            'data' => $arts,
            'total_result' => $total_result,
            'art_types' => $art_types,
            'url' => $url,
        ];
    }

    private function syncArtRecord(Crawler $crawler, string $id, string | null $token, bool $withCommentsCount): array {
        $tag_ids = $this->scrapeAllTagsAndStore($crawler);
        $art_category = $this->scrapeArtCategoryAndStore($crawler);

        $art = [
            'favorites_count' => $this->scrapeTotalFavorites($crawler),
            'title' => $crawler->filterXPath("//div[@property='dc:title']//h2[1]")->text(),
            'content' => $crawler->filterXPath("//div[@class='group-right right-column']/div[2]")->html(),
            'created_at' => Carbon::createFromFormat('l, F j, Y - H:i', $crawler->filterXPath("(//div[@class='field-item even'])[3]")->text())->format('Y-m-d H:i:s'),
            'art_category' => $art_category,
            'author_id' => $this->getAuthor($crawler, $token),
        ];

        if ($withCommentsCount) {
            $art['comments_count'] = $this->scrapeTotalComments($crawler);
        }

        $attributes = [
            'title' => $art['title'],
            'content' => $art['content'],
            'user_id' => $art['author_id'],
            'art_category_id' => $art_category->id,
            'favorites_count' => $art['favorites_count'],
            'created_at' => $art['created_at'],
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        if ($withCommentsCount) {
            $attributes['comments_count'] = $art['comments_count'];
        }

        $art_db = Art::updateOrCreate(['id' => $id], $attributes);
        $art_db->tags()->sync($tag_ids);

        return [
            'art_db' => $art_db,
            'art_category' => $art_category,
        ];
    }

    private function removeAllPreviousPreviews(string $art_id): void {
        ArtPreview::where('art_id', $art_id)->delete();
    }

    private function scrapeAllPreviewsAndStore(Crawler $crawler, ArtCategory $art_category, string $art_id): void {
        $previews = $art_category->name == 'Music' || $art_category->name == 'Sound Effect' ?
            $crawler->filterXPath("//div[@class='group-right right-column']/div[1]/div[@class='field-items']/div")->each(function (Crawler $node) {
                if ($node->filter('.audio-preview .audio-preview-container .play-button')->count() > 0) {
                    return [
                        'url' => $node->filter('.audio-preview .audio-preview-container .play-button')->attr('data-mp3-url'),
                        'art_preview_category_id' => ArtPreviewCategory::where('name', 'audio')->first()->id
                    ];
                }

                return [
                    'url' => $node->filter('a')->attr('href'),
                    'art_preview_category_id' => ArtPreviewCategory::where('name', 'image')->first()->id
                ];
            }) :
            $crawler->filterXPath("//div[@class='group-right right-column']/div[1]/div[@class='field-items']/div")->each(function (Crawler $node) {
                return [
                    'url' => $node->filter('a')->attr('href'),
                    'art_preview_category_id' => ArtPreviewCategory::where('name', 'image')->first()->id
                ];
            });

        foreach ($previews as $item) {
            ArtPreview::create([
                'art_id' => $art_id,
                'url' => $item['url'],
                'art_preview_category_id' => $item['art_preview_category_id']
            ]);
        }
    }

    private function removeLatestComment(string $art_id): void {
        ArtComment::where('art_id', $art_id)->latest()->limit(1)->delete();
    }

    private function scrapeLatestCommentAndStore(Crawler $crawler, string $art_id, string | null $token): void {
        $comments = $crawler->filter('#comments .comment')->each(function (Crawler $node) {
            $url_username = null;
            $username = null;

            if ($node->filter('.group-left span a')->count() > 0) {
                $url_username = str_replace('/users/', '', $node->filter('.group-left span a')->attr('href'));
                $username = $node->filter('.group-left span a')->text();
            }

            return [
                'content' => $node->filter('.group-right .field .field-items')->html(),
                'url_username' => $url_username,
                'username' => $username ?? 'Anonymous',
                'created_at' => Carbon::createFromFormat('m/d/Y - H:i', $node->filter('.group-left .field-name-post-date .field-items .field-item')->text())->format('Y-m-d H:i:s'),
            ];
        });

        if (!$comments) {
            return;
        }

        $item = $comments[0];
        $user_id = $this->authScraper->resolveUserId($item['url_username'], $item['username'], $token);

        ArtComment::create([
            'content' => $item['content'],
            'art_id' => $art_id,
            'user_id' => $user_id,
            'created_at' => $item['created_at']
        ]);
    }

    private function removeAllPreviousFiles(string $art_id): void {
        File::where('art_id', $art_id)->delete();
    }

    private function scrapeAllFilesAndStore(Crawler $crawler, string $art_id): void {
        $crawler->filter('.field-name-field-art-files .field-items')->each(function (Crawler $node) use ($art_id) {
            File::create([
                'art_id' => $art_id,
                'name' => $node->filter('.field-item .file a')->text(),
                'file_url' => $node->filter('.field-item .file a')->attr('href'),
                'file_size' => $node->filter('.field-item .file')->text(),
                'download_count' => $node->filter('.field-item .file .dlcount .dlcount-number')->text(),
            ]);
        });
    }

    private function scrapeArtCategoryAndStore(Crawler $crawler): ArtCategory {
        $art_category_name = $crawler->filterXPath("//a[@property='rdfs:label skos:prefLabel']")->text();
        $art_category_href = $crawler->filterXPath("//a[@property='rdfs:label skos:prefLabel']")->attr('href');

        $art_category_id = null;
        $query = parse_url($art_category_href, PHP_URL_QUERY);
        if ($query) {
            parse_str($query, $query_params);
            $type_ids = $query_params['field_art_type_tid'] ?? null;

            if (is_array($type_ids) && !empty($type_ids)) {
                $art_category_id = (int) $type_ids[0];
            } elseif (is_scalar($type_ids)) {
                $art_category_id = (int) $type_ids;
            }
        }

        if (!$art_category_id) {
            $art_category_id = (int) preg_replace('/\D+/', '', $art_category_href);
        }

        return ArtCategory::firstOrCreate([
            'id' => $art_category_id,
            'name' => $art_category_name
        ]);
    }

    private function scrapeTotalComments(Crawler $crawler): int {
        if ($crawler->filter('.pager-last')->count() > 0) {
            $last_page_url = $crawler->filter('.pager-last a')->attr('href');
            parse_str(parse_url($last_page_url, PHP_URL_QUERY), $query_params);
            $last_page_number = isset($query_params['page']) ? (int) $query_params['page'] : 0;

            $lastPageCrawler = $this->authScraper->authenticate('https://opengameart.org/' . $last_page_url, null, false);

            return ($last_page_number * 50) + (int) $lastPageCrawler->filter('#comments .comment')->count();
        }

        return (int) $crawler->filter('#comments .comment')->count();
    }

    private function scrapeAllTagsAndStore(Crawler $crawler): array {
        $tags = $crawler->filter('.field-name-field-art-tags .field-items .field-item a')->each(function (Crawler $node) {
            return [
                'name' => trim($node->text())
            ];
        });

        $process_tags = [];

        foreach ($tags as $item) {
            $tag = Tag::firstOrCreate([
                'name' => $item['name']
            ]);

            $process_tags[] = $tag->id;
        }

        return $process_tags;
    }

    private function scrapeTotalFavorites(Crawler $crawler): int {
        try {
            return (int) $crawler->filterXPath("//div[@id='block-system-main']/div[1]/div[1]/div[2]/div[7]/div[2]/div[1]")->text();
        } catch (Exception) {
            return 0;
        }
    }

    private function getAuthor(Crawler $crawler, string | null $token): int | null {
        $url_username = null;
        $username = null;

        if ($crawler->filter('div#block-system-main>div>div>div:nth-of-type(2)>div>div:nth-of-type(2)>div>strong>a')->count() > 0) {
            $url_username = preg_replace(
                '#^/users/#',
                '',
                $crawler->filter('div#block-system-main>div>div>div:nth-of-type(2)>div>div:nth-of-type(2)>div>strong>a')->attr('href')
            );
            $username = $crawler->filter('div#block-system-main>div>div>div:nth-of-type(2)>div>div:nth-of-type(2)>div>strong>a')->text();
        } elseif ($crawler->filterXPath("(//div[@class='field-item even'])[2]/span/a")->count() > 0) {
            $url_username = preg_replace('#^/users/#', '', $crawler->filterXPath("(//div[@class='field-item even'])[2]/span/a")->attr('href'));
            $username = $crawler->filterXPath("(//div[@class='field-item even'])[2]/span/a")->text();
        }

        return $this->authScraper->resolveUserId($url_username, $username, $token);
    }

    private function scrapeArtTypeAndStore(Crawler $crawler): void {
    }

    private function removeAllComments(string $art_id): void {
        ArtComment::where('art_id', $art_id)->delete();
    }

    private function scrapeAllCommentsAndStore(Crawler $crawler, string $art_id, string | null $token): void {
        $crawler->filter('#comments .comment')->each(function (Crawler $node) use ($art_id, $token) {
            $url_username = null;
            $username = null;

            if ($node->filter('.group-left span a')->count() > 0) {
                $url_username = str_replace('/users/', '', $node->filter('.group-left span a')->attr('href'));
                $username = $node->filter('.group-left span a')->text();
            }

            ArtComment::create([
                'content' => $node->filter('.group-right .field .field-items')->html(),
                'art_id' => $art_id,
                'user_id' => $this->authScraper->resolveUserId($url_username, $username, $token),
                'created_at' => Carbon::createFromFormat('m/d/Y - H:i', $node->filter('.group-left .field-name-post-date .field-items .field-item')->text())->format('Y-m-d H:i:s'),
            ]);
        });
    }

    private function scrapeLicensesAndStore(Crawler $crawler) {
        return $crawler->filter('.field-name-field-art-licenses .field-items .field-item a')->each(function (Crawler $node) {
            return License::firstOrCreate([
                'name' => $node->text(),
                'url' => $node->attr('href')
            ]);
        });
    }
}
