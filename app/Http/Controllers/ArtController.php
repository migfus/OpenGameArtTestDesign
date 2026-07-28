<?php

namespace App\Http\Controllers;

use App\Scraper\ArtScraper;
use Illuminate\Http\{JsonResponse, Request};

class ArtController extends Controller {
    public function __construct(private ArtScraper $artScraper) {}

    public function update(Request $req, string $id): JsonResponse {
        return response()->json($this->artScraper->update($id, $req->bearerToken()));
    }

    public function index(Request $req): JsonResponse {
        $req->validate([
            'search' => 'nullable|string|min:3',
            'page' => 'nullable|numeric',
            'field_art_type_tid' => ['required'],
            'field_art_tags_tid' => ['nullable', 'string'],
        ]);

        return response()->json($this->artScraper->index([
            'search' => $req->search,
            'page' => $req->page,
            'field_art_type_tid' => $req->integer('field_art_type_tid'),
            'field_art_tags_tid' => $req->string('field_art_tags_tid', '')->toString(),
        ], $req->bearerToken()));
    }

    public function show(Request $req, string $id): JsonResponse {
        return response()->json($this->artScraper->show($id, $req->bearerToken()));
    }
}
