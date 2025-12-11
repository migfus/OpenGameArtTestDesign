<?php

namespace App\Http\Controllers;

use App\Models\RecentForum;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class RecentForumController extends Controller {
    public function store(Request $req) {
        $req->validate([
            'id' => ['required']
        ]);

        // Scrape for forum
        $body = Http::timeout(10)->get("https://opengameart.org/forumtopic/{$req->id}")->body();
        $crawler = new Crawler($body);

        $content = $crawler->filterXPath("//div[@class='group-right right-column']/div[1]/div[1]/div[1]")->html();

        $raw_date = $crawler->filterXPath("//div[@id='block-system-main']/div[1]/div[1]/div[2]/div[2]/div[1]/div[1]")->text();

        $created_at = Carbon::createFromFormat('l, F j, Y - H:i', $raw_date)->format('Y-m-d H:i:s');

        $user_id = $crawler->filterXPath("//a[@class='username']")->attr('href');
        $user_id = preg_replace('#^/users/#', '', $user_id);

        $title = $crawler->filterXPath("//div[@property='dc:title']//h2[1]")->text();

        $recent_forum = [];

        // Check if user exists, if not create
        if (User::where('id', $user_id)->exists()) {
            $recent_forum = RecentForum::create([
                'id' => $req->id,
                'title' => $title,
                'user_id' => $user_id,
                'content' => $content,
                'created_at' => $created_at
            ]);
        } else {
            // Scrape for user based on recent_collection
            $body = Http::timeout(10)->get("https://opengameart.org/users/$user_id")->body();
            $crawler = new Crawler($body);

            $username = $crawler->filter('.username')->text();
            $image_url = $crawler->filterXPath("//img[@typeof='foaf:Image']")->attr('src');

            User::create([
                'id' => $user_id,
                'username' => $username,
                'image_url' => $image_url
            ]);

            $recent_forum = RecentForum::create([
                'id' => $req->id,
                'title' => $title,
                'user_id' => $user_id,
                'content' => $content,
                'created_at' => $created_at
            ]);
        }

        return response()->json($recent_forum->load('user'));
    }
}
