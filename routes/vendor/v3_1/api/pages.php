<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Vendor\V3_1\PageController;

Route::group(['as' => 'pages.'], function () {
    Route::get('/about', [PageController::class, 'about'])->name('v3_1.vendor.pages.about');
    Route::get('/terms', [PageController::class, 'terms'])->name('v3_1.vendor.pages.terms');
    Route::get('/privacy', [PageController::class, 'privacy'])->name('v3_1.vendor.pages.privacy');
});
