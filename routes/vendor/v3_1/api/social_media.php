<?php

use App\Http\Controllers\Api\Vendor\V3_1\SocialMediaController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/', [SocialMediaController::class, 'index']);
    Route::get('/{id}', [SocialMediaController::class, 'single']);
});

