<?php

use App\Http\Controllers\Api\Vendor\V3_1\VendorClientController;
use App\Http\Controllers\Api\Vendor\V3_1\VendorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Vendor\V3_1\VendorSocialMediaController;

Route::group(['middleware' => 'auth:api', 'as' => 'clients.'], function () {

    Route::get('/', [VendorClientController::class, 'index']);
    Route::post('/', [VendorClientController::class, 'store']);
    Route::delete('/{client}', [VendorClientController::class, 'destroy']);
    Route::get('/{client}/orders', [VendorClientController::class, 'getClientOrders']);

});
