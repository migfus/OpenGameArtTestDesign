<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\pages\HomePageController;
use App\Http\Controllers\RecentCollectionController;
use App\Http\Controllers\RecentForumController;

Route::group(['prefix' => '', 'as' => 'pages.'], function () {
    Route::resource('/', HomePageController::class)->only(['index']);

    Route::resource('/recent-collection', RecentCollectionController::class)->only(['update']);
    Route::resource('/recent-forum', RecentForumController::class)->only(['update']);
});
