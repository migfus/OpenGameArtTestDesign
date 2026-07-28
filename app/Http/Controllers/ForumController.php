<?php

namespace App\Http\Controllers;

use App\Models\RecentForum;
use App\Scraper\AuthScraper;

use Carbon\Carbon;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ForumController extends Controller {
    public function __construct(private AuthScraper $authScraper) {}

    public function update(Request $req, string $id): JsonResponse {
        $body = Http::timeout(10)->get("https://opengameart.org/forumtopic/{$id}")->body();
        $crawler = new Crawler($body);

        $url_username = str_replace('/users/', '', $crawler->filterXPath("//a[@class='username']")->attr('href'));
        $username = $crawler->filterXPath("//a[@class='username']")->text();

        $recent_forum = [
            'content' => $crawler->filterXPath("//div[@class='group-right right-column']/div[1]/div[1]/div[1]")->html(),

            'title' => $crawler->filterXPath("//div[@property='dc:title']//h2[1]")->text(),
            'user_id' => $this->authScraper->resolveUserId($url_username, $username, $req->bearerToken()),
            'created_at' =>
            Carbon::createFromFormat(
                'l, F j, Y - H:i',
                $crawler->filterXPath("//div[@id='block-system-main']/div[1]/div[1]/div[2]/div[2]/div[1]/div[1]")->text()
            )->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        RecentForum::updateOrCreate([
            'id' => $id
        ], $recent_forum);

        $recent_forum = RecentForum::where('id', $id)->first();

        return response()->json($recent_forum->load('user'));
    }
}
