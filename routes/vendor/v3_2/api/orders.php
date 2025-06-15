<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Vendor\V3_2\OrderController;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{id}', [OrderController::class, 'single']);
});
