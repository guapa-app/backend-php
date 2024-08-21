<?php

use App\Http\Controllers\Api\V3\VendorClientController;
use App\Http\Controllers\Api\V3\VendorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V3\VendorSocialMediaController;

Route::group(['middleware' => 'auth:api', 'as' => 'vendors.'], function () {
    Route::post('/',                                                        [VendorController::class, 'create'])->name('create');
    Route::match(['put', 'patch', 'post'], '/{id}',                [VendorController::class, 'update'])->name('update');

    Route::apiResource('{vendor}/clients', VendorClientController::class)->only(['index', 'store', 'destroy']);
    Route::get('/{vendor}/clients/{client}/orders', [VendorClientController::class, 'getClientOrders'])->name('client.orders');

    Route::apiResource('{vendor}/social-media', VendorSocialMediaController::class)->only(['store', 'update', 'destroy']);
});
