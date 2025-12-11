<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\pages\HomePageController;
use App\Http\Controllers\ArtController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\RecentForumController;

Route::group(['prefix' => '', 'as' => 'pages.'], function () {
    Route::resource('/', HomePageController::class)->only(['index']);

    Route::resource('/art', ArtController::class)->only(['store']);
    Route::resource('/recent-forum', RecentForumController::class)->only(['store']);
    Route::resource('/collection', CollectionController::class)->only(['store']);
});
