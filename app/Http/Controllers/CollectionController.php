<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class CollectionController extends Controller {
    public function store(Request $req) {
        $req->validate([
            'id' => ['required']
        ]);
        // Scrape for collection
        $body = Http::timeout(10)->get("https://opengameart.org/content/{$req->id}")->body();
        $crawler = new Crawler($body);

        $raw_date = $crawler->filterXPath("//div[@class='field-item even']")->eq(2)->text();
        $created_at = Carbon::createFromFormat('l, F j, Y - H:i', $raw_date)->format('Y-m-d H:i:s');

        $user_id = $crawler->filterXPath("//a[@class='username']")->attr('href');
        $user_id = preg_replace('#^/users/#', '', $user_id);

        $title = $crawler->filterXPath("//div[@property='dc:title']//h2[1]")->text();
        $content = $crawler->filterXPath("//div[@property='content:encoded']")->html();

        $recent_collection = [];

        // Check if user exists, if not create
        if (User::where('id', $user_id)->exists()) {
            $recent_collection = Collection::create([
                'id' => $req->id,
                'title' => $title,
                'content' => $content,
                'user_id' => $user_id,
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

            $recent_collection = Collection::create([
                'id' => $req->id,
                'user_id' => $user_id,
                'title' => $title,
                'content' => $content,
                'created_at' => $created_at
            ]);
        }

        return response()->json($recent_collection->load('user'));
    }
}
