<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\pages\HomePageController;

Route::group(['prefix' => '', 'as' => 'pages.'], function () {
    Route::resource('/', HomePageController::class)->only(['index']);
});
