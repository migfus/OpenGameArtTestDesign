<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{Art, ArtCategory, User};
use Carbon\Carbon;
use Exception;
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
        // $preview_files_content = $crawler->filterXPath("//div[@class='group-right right-column']/div[1]")->html();
        $content = $crawler->filterXPath("//div[@class='group-right right-column']/div[2]")->html();
        // $downloadable_files_content = $crawler->filterXPath("//div[@class='group-right right-column']/div[3]")->html();

        $art_category_name = $crawler->filterXPath("//a[@property='rdfs:label skos:prefLabel']")->text();
        $favorites_count = 0;
        $tags = [];

        $crawler->filterXPath("//div[text()='Tags: ']/following-sibling::div")->each(function (Crawler $node) use ($tags) {
            $tags[] = $node->filterXPath("//div/a")->html();
        });

        dd($tags);

        $tags = $this->processDatabaseTags($crawler->filterXPath("//div[text()='Tags: ']/following-sibling::div"));

        try {
            $favorites_count = (int) $crawler->filterXPath("//div[@id='block-system-main']/div[1]/div[1]/div[2]/div[7]/div[2]/div[1]")->text();
        } catch (Exception $e) {
        }

        $recent_collection = [];

        // Check if user exists, if not create
        if (User::where('id', $user_id)->exists()) {
            $art_category = ArtCategory::firstOrCreate([
                'name' => $art_category_name
            ]);

            $recent_collection = Art::firstOrCreate([
                'id' => $req->id,
                'title' => $title,
                'content' => $content,
                'user_id' => $user_id,
                'art_category_id' => $art_category->id,
                'favorites_count' => $favorites_count,
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

            $art_category = ArtCategory::firstOrCreate([
                'name' => $art_category_name
            ]);

            $recent_collection = Art::firstOrCreate([
                'id' => $req->id,
                'user_id' => $user_id,
                'art_category_id' => $art_category->id,
                'title' => $title,
                'content' => $content,
                'favorites_count' => $favorites_count,
                'created_at' => $created_at
            ]);
        }

        return response()->json($recent_collection->load(['user', 'artCategory']));
    }

    private function processDatabaseTags($tags) {
    }
}
