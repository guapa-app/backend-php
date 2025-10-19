<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Vendor\V3_1\OrderController;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{id}', [OrderController::class, 'single']);
    Route::get('/{id}/show-invoice', [OrderController::class, 'showInvoice']);
    Route::put( '/{id}', [OrderController::class, 'update']);
});
