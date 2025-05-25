<?php

use App\Http\Controllers\Api\V3\InfluencerController;
use App\Http\Controllers\Api\V3\VendorClientController;
use App\Http\Controllers\Api\V3\VendorController;
use App\Http\Controllers\Api\V3\VendorSocialMediaController;
use Illuminate\Support\Facades\Route;

Route::get('/',  [VendorController::class, 'index']);

Route::group(['middleware' => 'auth:api', 'as' => 'vendors.'], function () {
    Route::post('/', [VendorController::class, 'create']);
    Route::match(['put', 'patch', 'post'], '/{id}', [VendorController::class, 'update']);

    Route::apiResource('{vendor}/clients', VendorClientController::class)->only(['index', 'store', 'destroy']);
    Route::get('/{vendor}/clients/{client}/orders', [VendorClientController::class, 'getClientOrders']);

    Route::apiResource('{vendor}/social-media', VendorSocialMediaController::class)->only(['store', 'update', 'destroy'])->names('v3.vendors.social-media');
    Route::apiResource('{vendor}/influencers', InfluencerController::class)->only(['index', 'store', 'update', 'destroy'])->names('v3.vendors.influencers');
});
