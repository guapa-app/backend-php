<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\V3_1\PageController;

Route::get('/terms', [PageController::class, 'terms'])->name('v3_1.pages.terms');
Route::get('/about-us', [PageController::class, 'aboutUs'])->name('v3_1.pages.about_us');
Route::get('/{id}', [PageController::class, 'show'])->name('v3_1.pages.show');
Route::get('/about', [PageController::class, 'about'])->name('v3_1.pages.about');
Route::get('/privacy', [PageController::class, 'privacy'])->name('v3_1.pages.privacy');
