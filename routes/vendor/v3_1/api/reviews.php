<?php

use App\Http\Controllers\Api\Vendor\V3_1\ReviewController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/', [ReviewController::class, 'index']);
    Route::post('/', [ReviewController::class, 'create']);
});
