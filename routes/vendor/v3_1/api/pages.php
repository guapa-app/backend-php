<?php

use App\Http\Controllers\Api\Vendor\V3_1\PageController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'pages.'], function () {
    Route::get('/about-us', [PageController::class, 'aboutUs'])->name('about_us');
    Route::get('/terms', [PageController::class, 'terms'])->name('terms');
});
