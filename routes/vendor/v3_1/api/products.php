<?php

use App\Http\Controllers\Api\Vendor\V3_1\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'single']);
    Route::post('/', [ProductController::class, 'create']);
    Route::match(['put', 'patch', 'post'], '/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'delete']);
});
