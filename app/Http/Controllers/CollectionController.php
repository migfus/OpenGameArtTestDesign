<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Scraper\AuthScraper;

use Carbon\Carbon;
use Illuminate\Http\{JsonResponse, Request};
use Symfony\Component\DomCrawler\Crawler;

class CollectionController extends Controller {
    public function __construct(private AuthScraper $authScraper) {}

    private function parseCreatedAt(string $rawDate): string {
        $dateText = trim((string) preg_replace('/\s+/', ' ', html_entity_decode($rawDate)));

        try {
            return Carbon::createFromFormat('l, F j, Y - H:i', $dateText)->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            return Carbon::parse($dateText)->format('Y-m-d H:i:s');
        }
    }

    public function update(Request $req, string $id): JsonResponse {
        $crawler = $this->authScraper->authenticate("https://opengameart.org/content/{$id}", $req->bearerToken());

        $url_username = str_replace('/users/', '', $crawler->filterXPath("//a[@class='username']")->attr('href'));
        $username = $crawler->filterXPath("//a[@class='username']")->text();
        $forum = [

            'title' => $crawler->filterXPath("//div[@property='dc:title']//h2[1]")->text(),
            'content' => $crawler->filterXPath("//div[@class='group-right right-column']/div[2]")->html(),
            'user_id' => $this->authScraper->resolveUserId($url_username, $username, $req->bearerToken()),
            'created_at' =>
            Carbon::createFromFormat(
                'l, F j, Y - H:i',
                $crawler->filterXPath("//div[@class='field-item even']")->eq(2)->text()
            )->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $recent_collection = Collection::updateOrCreate([
            'id' => $id
        ], $forum);

        return response()->json($recent_collection->load('user'));
    }

    public function index(Request $req): JsonResponse {
        $crawler = $this->authScraper->authenticate("https://opengameart.org/collections", $req->bearerToken(), false);

        $collections = [];

        if ($crawler->filter('tbody tr')->count() > 0) {
            $crawler->filter('tbody tr')->each(function (Crawler $crawler) use (&$collections) {
                $collections[] = [
                    'title' => $crawler->filter('.views-field-title')->text(),
                    'id' => $crawler->filter('.views-field-title a')->attr('href'), //
                    'user' => ['username' => $crawler->filter('.views-field-name a')->text()],
                    'favorites_count' => $crawler->filter('.views-field-count')->text(),
                    'art_collected' => $crawler->filter('.views-field-field-collected-art')->text(),
                    'created_at' => $this->parseCreatedAt(
                        $crawler->filter('.views-field-changed')->text()
                    ),
                ];
            });
        }


        return response()->json($collections);
    }
}
