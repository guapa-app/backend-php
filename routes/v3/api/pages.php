<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V3\PageController;

Route::group(['as' => 'pages.'], function () {
    Route::get('/about',     [PageController::class, 'about'])->name('v3.pages.about');
    Route::get('/terms',      [PageController::class, 'terms'])->name('v3.pages.terms');
    Route::get('/privacy',    [PageController::class, 'privacy'])->name('v3.pages.privacy');
});
