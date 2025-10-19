<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V3\SupportMessageController;

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/',                                             [SupportMessageController::class, 'index']);
    Route::post('/',                 [SupportMessageController::class, 'create'])->name('v3.support.create');
});
