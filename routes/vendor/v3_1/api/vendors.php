<?php

use App\Http\Controllers\Api\Vendor\V3_1\InfluencerController;
use App\Http\Controllers\Api\Vendor\V3_1\VendorController;
use App\Http\Controllers\Api\Vendor\V3_1\VendorSocialMediaController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api', 'as' => 'vendors.'], function () {
    Route::post('/', [VendorController::class, 'create']);
    Route::match(['put', 'patch', 'post'], '/update-profile', [VendorController::class, 'update']);

    Route::put('/activate-wallet', [VendorController::class, 'activateWallet']);

    Route::match(['put', 'post'],'/working-days', [VendorController::class, 'updateWorkingDays']);

    Route::get('/working-days', [VendorController::class, 'getWorkingDays']);

    Route::apiResource('{vendor}/social-media', VendorSocialMediaController::class)->only([
        'store', 'update', 'destroy'
    ])->names('vendor.v3_1.vendors.social-media');
    Route::apiResource('influencers', InfluencerController::class)->only([
        'index', 'store', 'update', 'destroy'
    ])->names('vendor.v3_1.vendors.influencers');
});
