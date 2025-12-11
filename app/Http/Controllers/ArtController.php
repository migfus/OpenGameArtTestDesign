<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{Art, User};
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ArtController extends Controller {

    public function store(Request $req) {
        $req->validate([
            'id' => ['required']
        ]);


        // Scrape for collection
        $body = Http::timeout(10)->get("https://opengameart.org/content/{$req->id}")->body();
        $crawler = new Crawler($body);


        $raw_date = $crawler->filterXPath("(//div[@class='field-item even'])[3]")->text();
        $created_at = Carbon::createFromFormat('l, F j, Y - H:i', $raw_date)->format('Y-m-d H:i:s');

        $user_id = preg_replace('#^/users/#', '', $crawler->filterXPath("(//div[@class='field-item even'])[2]/span/a")->attr('href'));


        $title = $crawler->filterXPath("//div[@property='dc:title']//h2[1]")->text();
        $preview_files_content = $crawler->filterXPath("//div[@class='group-right right-column']/div[1]")->html();
        $content = $crawler->filterXPath("//div[@class='group-right right-column']/div[2]")->html();
        $downloadable_files_content = $crawler->filterXPath("//div[@class='group-right right-column']/div[3]")->html();

        $recent_collection = [];

        // Check if user exists, if not create
        if (User::where('id', $user_id)->exists()) {
            $recent_collection = Art::create([
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

            $recent_collection = Art::create([
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
