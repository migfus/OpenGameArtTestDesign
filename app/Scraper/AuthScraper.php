<?php

namespace App\Scraper;

use App\Models\{User, UserSession};
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Symfony\Component\DomCrawler\Crawler;

class AuthScraper {
	public function authenticate(string $url, string | null $token, bool $wait = false): Crawler {
		$cookies = UserSession::where('id', $token)->get();

		$cookieArray = [];

		foreach ($cookies as $cookie) {
			$cookieArray[$cookie->name] = $cookie->value;
		}

		$cookieJar = CookieJar::fromArray($cookieArray, 'opengameart.org');

		$client = new Client([
			'cookies' => $cookieJar,
			'allow_redirects' => true,
			'headers' => ['User-Agent' => 'Mozilla/5.0'],
			'verify' => false,
		]);

		$response = $client->get($url);

		return new Crawler((string) $response->getBody());
	}

	public function scrapeUserAndStore(string $url_username, string $username, string | null $token): User {
		try {
			$crawler = $this->authenticate("https://opengameart.org/users/" . $url_username, $token);

			$user_id = str_replace('/collections', '', str_replace('/user/', '', $crawler->filter('div#right>div>ul>li:nth-of-type(2)>a')->attr('href')));
			$image_url = $crawler->filterXPath("//img[@typeof='foaf:Image']")->attr('src');

			return User::firstOrCreate(['id' => $user_id], [
				'url_username' => $url_username,
				'username' => $username,
				'image_url' => $image_url,
			]);
		} catch (\Exception) {
			return User::firstOrCreate(['id' => rand(100000, 999999)], [
				'url_username' => $url_username,
				'username' => $username,
				'image_url' => ''
			]);
		}
	}

	public function resolveUserId(string | null $url_username, string | null $username, string | null $token): int | null {
		if (!$url_username) {
			return null;
		}

		$existingUser = User::where('url_username', $url_username)->first();

		if ($existingUser) {
			return $existingUser->id;
		}

		return $this->scrapeUserAndStore($url_username, $username ?? 'Anonymous', $token)->id;
	}

}
