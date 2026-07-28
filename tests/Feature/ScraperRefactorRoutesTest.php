<?php

use App\Models\Art;
use App\Scraper\ArtScraper;
use App\Scraper\AuthScraper;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\DomCrawler\Crawler;

beforeEach(function () {
    Cache::flush();
});

afterEach(function () {
    \Mockery::close();
});

it('uses the art scraper for the arts index route', function () {
    $mock = \Mockery::mock(ArtScraper::class);
    $mock->shouldReceive('index')
        ->once()
        ->with([
            'search' => 'trees',
            'page' => '2',
            'field_art_type_tid' => 9,
            'field_art_tags_tid' => 'forest',
        ], 'token-123')
        ->andReturn([
            'data' => [['id' => 'art-1', 'title' => 'Forest Pack']],
            'total_result' => 1,
            'art_types' => [],
            'url' => 'https://example.test/search',
        ]);

    $this->app->instance(ArtScraper::class, $mock);

    $this->withToken('token-123')
        ->getJson('/api/arts?search=trees&page=2&field_art_type_tid=9&field_art_tags_tid=forest')
        ->assertOk()
        ->assertJsonPath('data.0.id', 'art-1')
        ->assertJsonPath('total_result', 1);
});

it('uses the art scraper for the arts show route', function () {
    $art = new Art([
        'id' => 'art-42',
        'title' => 'Sky Temple',
    ]);

    $mock = \Mockery::mock(ArtScraper::class);
    $mock->shouldReceive('show')
        ->once()
        ->with('art-42', 'token-abc')
        ->andReturn($art);

    $this->app->instance(ArtScraper::class, $mock);

    $this->withToken('token-abc')
        ->getJson('/api/arts/art-42')
        ->assertOk()
        ->assertJsonPath('id', 'art-42')
        ->assertJsonPath('title', 'Sky Temple');
});

it('uses the art scraper for the arts update route', function () {
    $art = new Art([
        'id' => 'art-55',
        'title' => 'Dungeon Theme',
    ]);

    $mock = \Mockery::mock(ArtScraper::class);
    $mock->shouldReceive('update')
        ->once()
        ->with('art-55', 'token-update')
        ->andReturn($art);

    $this->app->instance(ArtScraper::class, $mock);

    $this->withToken('token-update')
        ->putJson('/api/arts/art-55')
        ->assertOk()
        ->assertJsonPath('id', 'art-55')
        ->assertJsonPath('title', 'Dungeon Theme');
});

it('uses the auth scraper for the homepage route', function () {
    Cache::put('get_recent_collections', [
        ['id' => 'collection-1', 'title' => 'Recent Collection', 'content' => null, 'user' => null],
    ], 60);
    Cache::put('get_recent_forums', [
        ['id' => 'forum-1', 'title' => 'Recent Forum', 'user_id' => null, 'created_at' => null, 'user' => null],
    ], 60);
    Cache::put('get_affiliates', [
        ['id' => 'affiliate-1', 'title' => 'Affiliate', 'image_url' => null],
    ], 60);
    Cache::put('get_posts', [
        [
            'title' => 'Banner Post',
            'link' => '/blog/banner',
            'author_name' => 'Author',
            'author_link' => '/users/author',
            'date' => '2026-07-20',
            'content_html' => '<p>Body</p>',
            'comment_link' => '/blog/banner#comments',
            'author_image' => '/images/author.png',
        ],
    ], 60);
    Cache::put('get_weekly_arts', [
        ['id' => 'weekly-1', 'title' => 'Weekly Art'],
    ], 60);
    Cache::put('get_new_arts', [
        ['id' => 'new-1', 'title' => 'New Art'],
    ], 60);
    Cache::put('donation_monthly_value', '$123', 60);

    $mock = \Mockery::mock(AuthScraper::class);
    $mock->shouldReceive('authenticate')
        ->once()
        ->with('https://opengameart.org', 'home-token')
        ->andReturn(new Crawler('<html></html>'));

    $this->app->instance(AuthScraper::class, $mock);

    $this->withToken('home-token')
        ->getJson('/api')
        ->assertOk()
        ->assertJsonPath('recent_collections.0.id', 'collection-1')
        ->assertJsonPath('recent_forum.0.id', 'forum-1')
        ->assertJsonPath('affiliates.0.id', 'affiliate-1')
        ->assertJsonPath('latest_banner_title', 'Banner Post')
        ->assertJsonPath('weekly_arts.0.id', 'weekly-1')
        ->assertJsonPath('new_arts.0.id', 'new-1')
        ->assertJsonPath('donation_monthly_value', '$123');
});
