<?php

use App\Http\Controllers\Api\User\V3_1\PageController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'pages.'], function () {
    Route::get('/terms', [PageController::class, 'terms'])->name('terms');
    Route::get('/about-us', [PageController::class, 'aboutUs'])->name('about_us');
    Route::get('/{id}', [PageController::class, 'show'])->name('show');
});
