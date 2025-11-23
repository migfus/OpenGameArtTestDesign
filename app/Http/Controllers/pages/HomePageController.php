<?php

namespace App\Http\Controllers\pages;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class HomePageController extends Controller {


    public function index() {
        try {
            $body = Http::timeout(10)->get('https://opengameart.org')->body();

            $crawler = new Crawler($body);

            $recent_collections = $crawler->filter('.view-art-collections .view-content .item-list ul li .views-field-title a')
                ->each(function (Crawler $node) {
                    return [
                        'title' => trim($node->text()),
                        'link'  => $node->attr('href'),
                    ];
                });

            $recent_forum = $crawler->filter('.view-new-forum-topics .view-content .item-list ul li')
                ->each(function (Crawler $node) {
                    return [
                        'title'     => trim($node->filter('.views-field-title a')->text()),
                        'link'      => $node->filter('.views-field-title a')->attr('href'),
                        'time_ago'  => trim($node->filter('.views-field-last-comment-timestamp .placeholder')->text()),
                        'username'  => trim($node->filter('.views-field-last-comment-name a.username')->text()),
                    ];
                });

            $affiliates = $crawler->filter('.view-links .view-content .item-list ul li')
                ->each(function (Crawler $node) {
                    $aTag = $node->filter('a');
                    return [
                        'title' => trim($aTag->text()),
                        'href'  => $aTag->attr('href'),
                    ];
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
        } catch (\Exception $e) {
            return response([
                'content' => $e,
                'title' => '500 Error',
            ], 500);
        }
    }
}
