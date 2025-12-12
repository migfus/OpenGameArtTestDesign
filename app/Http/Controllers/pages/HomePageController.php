<?php

namespace App\Http\Controllers\pages;

use App\Models\Affiliate;
use App\Models\Art;
use App\Models\Collection;
use App\Models\RecentForum;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class HomePageController extends Controller {
    public function index() {
        // try {
        $body = Http::timeout(10)->get('https://opengameart.org')->body();

        $crawler = new Crawler($body);

        $recent_collections = $crawler->filter('.view-art-collections .view-content .item-list ul li .views-field-title a')
            ->each(function (Crawler $node) {
                $id =  preg_replace('#^/content/#', '', $node->attr('href'));
                $title = trim($node->text());

                return $this->processRecentCollections($id, $title);
            });

        $recent_forum = $crawler->filter('.view-new-forum-topics .view-content .item-list ul li')
            ->each(function (Crawler $node) {
                $id = preg_replace('#^/forumtopic/#', '', $node->filter('.views-field-title a')->attr('href'));
                $title = trim($node->filter('.views-field-title a')->text());

                return $this->processRecentForums($id, $title);
            });

        $affiliates = $crawler->filter('.view-links .view-content .item-list ul li')
            ->each(function (Crawler $node) {
                $aTag = $node->filter('a');
                return $this->getAffiliateFromDatabaseIfExisted($aTag->attr('href'), trim($aTag->text()));
            });

        $posts = $crawler->filter('.view-blog .view-content .views-row')->each(function (Crawler $node) {
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

        $weekly_arts = $crawler->filter('#block-views-art-block-9 .content .view-art .view-content .views-row')->each(function (Crawler $node) {
            $titleNode = $node->filter('.field-name-title a');
            $previewImgNode = $node->filter('.field-name-field-art-preview img');
            $playButtonNode = $node->filter('.play-button');

            $id = preg_replace('#^/content/#', '', $titleNode->attr('href'));
            // $user_id = null;
            $title = trim($titleNode->text());
            // $preview_image = $previewImgNode->attr('src');


            // return $this->processWeeklyArt($titleNode->attr('href'));

            // if ($playButtonNode->count()) {
            //     $item['audio_ogg'] = $playButtonNode->attr('data-ogg-url');
            //     $item['audio_mp3'] = $playButtonNode->attr('data-mp3-url');
            //     return $item;
            // } else {

            //     return $item;
            // }

            return $this->processWeeklyArt($id, $title);
        });

        $new_arts = $crawler->filter('#block-views-art-block-6 .content .view-art .view-content .views-row')->each(function (Crawler $node) {
            $titleNode = $node->filter('.field-name-title a');
            $previewImgNode = $node->filter('.field-name-field-art-preview img');
            $playButtonNode = $node->filter('.play-button');

            // title: string
            // link: string
            // preview_image: string [audio / image]
            // audio_ogg?: string
            // audio_mp3?: string

            $item = [
                'title' => trim($titleNode->text()),
                'link' => $titleNode->attr('href'),
                'preview_image' => $previewImgNode->attr('src'),
            ];

            if ($playButtonNode->count()) {
                $item['audio_ogg'] = $playButtonNode->attr('data-ogg-url');
                $item['audio_mp3'] = $playButtonNode->attr('data-mp3-url');
                return $item;
            } else {

                return $item;
            }
        });

        return response()->json([
            'recent_collections' => array_slice($recent_collections, 0, 5),
            'recent_forum' => array_slice($recent_forum, 0, 5),
            'affiliates' => $affiliates,
            'latest_banner_title' => $posts[0]['title'],
            'weekly_arts' => array_slice($weekly_arts, 0, 12),
            'new_arts' => array_slice($new_arts, 0, 12),
            'posts' => $posts
        ]);
        // } catch (\Exception $e) {
        //     return response([
        //         'content' => $e,
        //         'title' => '500 Error',
        //     ], 500);
        // }
    }


    private function processRecentCollections(string $id, string $title) {
        $id = urldecode($id);
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
    }

    private function processRecentForums(string $id, string $title) {
        $id = urldecode($id);
        $id = parse_url($id, PHP_URL_PATH);

        if (RecentForum::where('id', $id)->exists()) {
            return RecentForum::where('id', $id)->with('user')->first()->toArray();
        } else {
            // OPTIMIZE: maybe transfer to update instead
            // RecentForum::create([
            //     'id' => $id,
            //     'user_id' => null,
            //     'title' => $title,
            //     'content' => null,
            // ]);

            return [
                'id' => $id,
                'user_id' => null,
                'title'     => $title,
                'created_at' => null,
                'user'  => null,
            ];
        }
    }

    private function processWeeklyArt(string $id, string $title) {
        $id = urldecode($id);
        // Checks if existed
        // If existed, just return the old data (complete than scraped)
        if (Art::where('id', $id)->exists()) {
            return Art::where('id', $id)->with('user')->first()->toArray();
        }
        // If not existed then create new temporary data (needs to reupdate later)
        else {

            return [
                'id' => $id,
                'title' => $title,
                'user' => null,
            ];
        }
    }

    private function getAffiliateFromDatabaseIfExisted(string $id, string $title) {
        if (Affiliate::where('id', $id)->exists()) {
            return Affiliate::where('id', $id)->first()->toArray();
        }

        return [
            'id' => $id,
            'title' => $title,
            'image_url' => null,
        ];
    }
}
