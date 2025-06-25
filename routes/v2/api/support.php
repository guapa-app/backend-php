<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V2\SupportMessageController;

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/',                                             [SupportMessageController::class, 'index']);
    Route::post('/contact',                 [SupportMessageController::class, 'create'])->name('v2.support.create');
});
