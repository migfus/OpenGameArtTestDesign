<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class AffiliateController extends Controller {

    public function store(Request $req) {
        // STUB Vulnerability? 🤣
        // STUB accepts any id (url), what could go wrong?
        $req->validate([
            'id' => ['required'], // url
            'title' => ['required']
        ]);

        # Scrape for forum
        $body = Http::timeout(10)->get($req->id)->body();
        $crawler = new Crawler($body);

        // Checks any possible icon to find
        $image_url = $this->searchAllIcons($crawler);

        if (!Affiliate::where('id', $req->id)->exists()) {
            $affiliate = Affiliate::create([
                'id' => $req->id,
                'title' => $req->title,
                'image_url' => $image_url
            ]);

            return $affiliate->toArray();
        }
    }

    private function searchAllIcons($crawler) {
        $selectors = [
            'link[rel="icon"]',
            'link[rel="shortcut icon"]',
            'link[rel="apple-touch-icon"]',
            'link[rel="apple-touch-icon-precomposed"]',
            'meta[property="og:image"]',
            'meta[name="msapplication-TileImage"]'
        ];

        foreach ($selectors as $selector) {
            $node = $crawler->filter($selector);

            if ($node->count() > 0) {
                $icon_url = $node->first()->attr('href') ?? $node->first()->attr('content');

                return $icon_url;
            }
        }

        return null;
    }
}
