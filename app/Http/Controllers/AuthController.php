<?php

namespace App\Http\Controllers;

use App\Models\{
    ArtPreview,
    ArtPreviewCategory,
    UserSession
};

use App\Scraper\AuthScraper;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Http\{JsonResponse, Request};
use Symfony\Component\DomCrawler\Crawler;

class AuthController extends Controller {
    public function __construct(private AuthScraper $authScraper) {}

    public function login(Request $req): JsonResponse {
        $req->validate([
            'username' => ['required'],
            'password' => ['required']
        ]);

        $cookieJar = new CookieJar();

        $client = new Client([
            'cookies' => $cookieJar,
            'allow_redirects' => true,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0',
            ],
            'verify' => false,
        ]);

        $response = $client->get('https://opengameart.org');
        $crawler = new Crawler($response->getBody());

        $html = $client->post('https://opengameart.org/node', [
            'form_params' => [
                'destination' => 'node',
                'name' => $req->username,
                'pass' => $req->password,
                'form_id' =>  $crawler->filter('input[name="form_id"]')->attr('value'),
                'openid_return_to' => 'https://opengameart.org/openid/authenticate?destination=node',
                'form_build_id' => $crawler->filter('input[type="hidden"][name="form_build_id"]')->attr('value')
            ],
        ])->getBody();

        $crawler = new Crawler($html);
        $user_session = [];

        $url_username = str_replace('/users/', '', $crawler->filter('.active a')->attr('href'));
        $username = $crawler->filter('.active a')->text();

        $user_id =  $this->authScraper->scrapeUserAndStore($url_username, $username, '')->id;

        foreach ($cookieJar->toArray() as $cookie) {
            $user_session = UserSession::create([
                'user_id' => $user_id,
                'name'     => $cookie['Name'],
                'value'    => $cookie['Value'],
                'domain'   => $cookie['Domain'],
                'path'     => $cookie['Path'],
                'secure'   => $cookie['Secure'],
                'http_only' => $cookie['HttpOnly'],
                'expires'  => $cookie['Expires'],
            ]);
        }

        $data['token'] = $user_session->id;

        $crawler = $this->authScraper->authenticate('https://opengameart.org/', $data['token']);

        // dd($)

        try {
            $user_id = str_replace('/users/', '', $crawler->filter("#block-oga-hello div a")->attr('href'));
        } catch (\InvalidArgumentException $err) {
            return response()->json(false);
        }

        $response = $client->get('https://opengameart.org/users/' . $user_id);
        $crawler = new Crawler($response->getBody());

        $data['auth'] = [
            'id' => $user_id,
            'username' => $crawler->filter('.username')->text(),
            'image_url' => $crawler->filterXPath("//img[@typeof='foaf:Image']")->attr('src'),
        ];

        return response()->json($data);
    }

    public function logout(Request $req): JsonResponse {
        $req->validate([
            'token' => ['required']
        ]);

        $user_session =  UserSession::where('id', $req->token)->delete();

        // return true;
        return response()->json(true);
    }

    public function artPreviews(): JsonResponse {
        $art_preview_category_id = ArtPreviewCategory::where('name', 'image')->first()->id;
        $art_previews = ArtPreview::where('art_preview_category_id', $art_preview_category_id)->inRandomOrder()->limit(100)->get();

        return response()->json($art_previews);
    }

    public function getFriends(Request $req) {
        $user_session = UserSession::where('id', $req->bearerToken())->with('user')->first();

        // return response()->json();

        $crawler = $this->authScraper->authenticate('https://opengameart.org/user/' . $user_session->user->id . '/friends', $req->bearerToken());

        dd(
            $crawler->filter(".view-friends .view-content .item-list ul li")->each(function (Crawler $node) {
                return [
                    'username' => $node->filter('.views-field-name .field-content a')->text(),
                    'url_username' => str_replace('/users/', '', $node->filter('.views-field-name .field-content a')->attr('href')),

                ];
            })
        );
        // $url_username = User::find($user_id)->url_username;
    }
}
