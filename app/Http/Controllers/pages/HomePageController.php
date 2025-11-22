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




            return response()->json([
                'recent_collections' => array_slice($recent_collections, 0, 5)
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
