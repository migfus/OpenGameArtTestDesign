<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\{Affiliate, Art, Collection, RecentForum};
use App\Scraper\AuthScraper;

use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class HomePageController extends Controller {
    protected $cache_duration = 5; // Cache duration in seconds

    public function __construct(private AuthScraper $authScraper) {}

    public function index(Request $req): JsonResponse {
        $crawler = $this->authScraper->authenticate('https://opengameart.org', $req->bearerToken());

        $recent_collections = $this->getRecentCollection($crawler);

        $recent_forum = $this->getRecentForums($crawler);

        $affiliates = $this->getAffiliates($crawler);

        $posts = $this->getPosts($crawler);

        $weekly_arts = $this->getWeeklyArts($crawler);

        $new_arts = $this->getNewArts($crawler);

        return response()->json([
            'recent_collections' => array_slice($recent_collections, 0, 5),
            'recent_forum' => array_slice($recent_forum, 0, 5),
            'affiliates' => $affiliates,
            'latest_banner_title' => $posts[0]['title'],
            'weekly_arts' => array_slice($weekly_arts, 0, 12),
            'new_arts' => array_slice($new_arts, 0, 12),
            'posts' => $posts,
            'donation_monthly_value' => $this->getDonation(),
        ]);
    }

    private function getRecentCollection(Crawler $crawler): array {
        return Cache::remember('get_recent_collections', $this->cache_duration, function () use ($crawler) {
            return $crawler->filter('.view-art-collections .view-content .item-list ul li .views-field-title a')
                ->each(function (Crawler $node) {
                    $id = urldecode(preg_replace('#^/content/#', '', $node->attr('href')));
                    $title = trim($node->text());

                    // Checks if existed
                    // If existed, just return the old data (complete than scraped)
                    if (Collection::where('id', $id)->exists()) {
                        return Collection::where('id', $id)->with('user')->first()->toArray();
                    }
                    // If not existed then create new temporary data (needs to reupdate later)
                    else {

                        return [
                            'id' => $id,
                            'title' => $title,
                            'content' => null,
                            'user' => null,
                        ];
                    }
                });
        });
    }

    private function getRecentForums(Crawler $crawler): array {
        return Cache::remember('get_recent_forums', $this->cache_duration, function () use ($crawler) {


            return $crawler->filter('.view-new-forum-topics .view-content .item-list ul li')
                ->each(function (Crawler $node) {
                    $id = parse_url(urldecode(preg_replace('#^/forumtopic/#', '', $node->filter('.views-field-title a')->attr('href'))), PHP_URL_PATH);
                    $title = trim($node->filter('.views-field-title a')->text());

                    if (RecentForum::where('id', $id)->exists()) {
                        return RecentForum::where('id', $id)->with('user')->first()->toArray();
                    } else {
                        return [
                            'id' => $id,
                            'user_id' => null,
                            'title'     => $title,
                            'created_at' => null,
                            'user'  => null,
                        ];
                    }
                });
        });
    }

    private function getAffiliates(Crawler $crawler): array {
        return Cache::remember('get_affiliates', $this->cache_duration, function () use ($crawler) {
            return $crawler->filter('.view-links .view-content .item-list ul li')
                ->each(function (Crawler $node) {
                    $aTag = $node->filter('a');

                    if (Affiliate::where('id', $aTag->attr('href'))->exists()) {
                        return Affiliate::where('id', $aTag->attr('href'))->first()->toArray();
                    }

                    return [
                        'id' => $aTag->attr('href'),
                        'title' => trim($aTag->text()),
                        'image_url' => null,
                    ];
                });
        });
    }

    private function getPosts(Crawler $crawler): array {
        return Cache::remember('get_posts', $this->cache_duration, function () use ($crawler) {
            return $crawler->filter('.view-blog .view-content .views-row')->each(function (Crawler $node) {
                $titleNode = $node->filter('.field-name-title h2 a');
                $bylineNode = $node->filter('.field-name-byline');
                $bodyNode = $node->filter('.field-name-body .field-item');
                $commentNode = $node->filter('.field-name-comment-count-link a');
                $authorImgNode = $node->filter('.field-name-ds-user-picture a img');

                return [
                    'title'        => trim($titleNode->text()),
                    'link'         => $titleNode->attr('href'),
                    'author_name'  => trim($bylineNode->filter('a')->text()),
                    'author_link'  => $bylineNode->filter('a')->attr('href'),
                    'date'         => trim(preg_replace('/By .* on /', '', $bylineNode->text())),
                    'content_html' => $bodyNode->html(),
                    'comment_link' => $commentNode->attr('href'),
                    'author_image' => $authorImgNode->attr('src'),
                ];
            });
        });
    }

    private function getWeeklyArts(Crawler $crawler): array {
        return Cache::remember('get_weekly_arts', $this->cache_duration, function () use ($crawler) {
            return $crawler->filter('#block-views-art-block-9 .content .view-art .view-content .views-row')->each(function (Crawler $node) {
                $titleNode = $node->filter('.field-name-title a');
                $previewImgNode = $node->filter('.field-name-field-art-preview img');
                $playButtonNode = $node->filter('.play-button');

                $id = preg_replace('#^/content/#', '', $titleNode->attr('href'));
                $title = trim($titleNode->text());

                $image_preview = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';
                if($previewImgNode->filter('img')->count()) {
                    $image_preview = $previewImgNode->filter('img')->attr('src');
                }

                $previews = [];
                $type = 'Art';
                $preview_type = 'image';
                if ($playButtonNode->count()) {
                    $previews[] = $playButtonNode->attr('data-mp3-url');
                    $type = 'Music';
                    $preview_type = 'audio';
                } else {
                    $previews[] = $previewImgNode->attr('src');
                }

                return $this->getArtsFromDatabaseIfExists($id, $title, $previews, $type, $preview_type, $image_preview);
            });
        });
    }

    private function getNewArts(Crawler $crawler): array {
        return Cache::remember('get_new_arts', $this->cache_duration, function () use ($crawler) {
            return $crawler->filter('#block-views-art-block-6 .content .view-art .view-content .views-row')->each(function (Crawler $node) {
                $titleNode = $node->filter('.field-name-title a');
                $previewImgNode = $node->filter('.field-name-field-art-preview img');
                $playButtonNode = $node->filter('.play-button');

                $id = preg_replace('#^/content/#', '', $titleNode->attr('href'));
                $title = trim($titleNode->text());

                $image_preview = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';
                if($previewImgNode->filter('img')->count()) {
                    $image_preview = $previewImgNode->filter('img')->attr('src');
                }

                $previews = [];
                $type = 'Art';
                $preview_type = 'image';
                if ($playButtonNode->count()) {
                    $previews[] = $playButtonNode->attr('data-mp3-url');
                    $type = 'Music';
                    $preview_type = 'audio';
                } else {
                    $previews[] = $previewImgNode->attr('src');
                }

                return $this->getArtsFromDatabaseIfExists($id, $title, $previews, $type, $preview_type, $image_preview);

                // title: string
                // link: string
                // preview_image: string [audio / image]
                // audio_ogg?: string
                // audio_mp3?: string

                // $item = [
                //     'title' => trim($titleNode->text()),
                //     'link' => $titleNode->attr('href'),
                //     'preview_image' => $previewImgNode->attr('src'),
                // ];

                // if ($playButtonNode->count()) {
                //     $item['audio_ogg'] = $playButtonNode->attr('data-ogg-url');
                //     $item['audio_mp3'] = $playButtonNode->attr('data-mp3-url');
                //     return $item;
                // } else {

                //     return $item;
                // }
            });
        });
    }

    private function getArtsFromDatabaseIfExists(string $id, string $title, array $previews, string $type, string $preview_type, string $image_preview): array {
        $id = urldecode($id);
        // Checks if existed
        // If existed, just return the old data (complete than scraped)
        if (Art::where('id', $id)->exists()) {
            return Art::query()
                ->where('id', $id)
                ->with(['user', 'art_category', 'art_previews.art_preview_category', 'files', 'art_comments.user'])
                ->first()
                ->toArray();
        }
        // If not existed then create new temporary data (needs to reupdate later)
        else {
            Art::create([
                'id' => $id,
                'title' => $title,
                'user_id' => null,
                'content' => '',
                'art_category_id' => null,
                'favorites_count' => 0,
                'comments_count' => 0,
                'image_preview' => $image_preview
            ]);

            return [
                'id' => $id,
                'title' => $title,
                'user' => [
                    'image_url' => env('APP_URL') . '/images/icon.png'
                ],
                'art_category' => [
                    'name' => $type
                ],
                'previews' => $previews,
                'art_comments' => [],
                'files' => [],

            ];
        }
    }

    private function getDonation() {
        return Cache::remember('donation_monthly_value', 60 * 60 * 24, function () {
            $client = new Client();

            $cookieArray['patreon_locale_code'] = 'en-US';
            $cookieArray['patreon_location_country_code'] = 'US';

            $cookieJar = CookieJar::fromArray($cookieArray, 'opengameart.org');

            $client = new Client([
                'cookies' => $cookieJar,
                'allow_redirects' => true,
                'headers' => ['User-Agent' => 'Mozilla/5.0'],
                'verify' => false,
            ]);

            $response = $client->get('https://www.patreon.com/opengameart');
            $html = (string) $response->getBody();

            $crawler = new Crawler($html);
            return $crawler->filter('span[data-tag="earnings-count"]')->text();
        });
    }
}
