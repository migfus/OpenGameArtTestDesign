<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{RecentCollection, User};
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class RecentCollectionController extends Controller {

    public function update(Request $req, RecentCollection $recent_collection) {
        // Scrape for collection
        $body = Http::timeout(10)->get("https://opengameart.org/content/{$recent_collection->id}")->body();
        $crawler = new Crawler($body);

        $raw_date = $crawler->filterXPath("//div[@class='field-item even']")->eq(2)->text();
        $created_at = Carbon::createFromFormat('l, F j, Y - H:i', $raw_date)->format('Y-m-d H:i:s');

        $user_id = $crawler->filterXPath("//a[@class='username']")->attr('href');
        $user_id = preg_replace('#^/users/#', '', $user_id);

        // Check if user exists, if not create
        if (User::where('id', $user_id)->exists()) {
            $recent_collection->update(['user_id' => $user_id, 'created_at' => $created_at]);
        } else {
            // Scrape for user based on recent_collection
            $body = Http::timeout(10)->get("https://opengameart.org/users/$user_id")->body();
            $crawler = new Crawler($body);

            $username = $crawler->filter('.username')->text();
            $image_url = $crawler->filterXPath("//img[@typeof='foaf:Image']")->attr('src');

            $user = User::create([
                'id' => $user_id,
                'username' => $username,
                'image_url' => $image_url
            ]);

            $recent_collection->update(['user_id', $user_id, 'created_at' => $created_at]);
        }

        return response()->json($recent_collection->load('user'));
    }
}
