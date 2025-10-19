<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V3\VendorController;
use App\Http\Controllers\Api\V3\InfluencerController;
use App\Http\Controllers\Api\V3\VendorClientController;
use App\Http\Controllers\Api\V3\VendorSocialMediaController;

Route::get('/',  [VendorController::class, 'index'])->name('v3.vendors.list');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/', [VendorController::class, 'create'])->name('v3.vendors.create');
    Route::match(['put', 'patch', 'post'], '/{id}', [VendorController::class, 'update'])->name('v3.vendors.update');

    Route::apiResource('{vendor}/clients', VendorClientController::class)->only(['index', 'store', 'destroy'])
        ->names([
            'index' => 'v3.vendors.clients.index',
            'store' => 'v3.vendors.clients.store',
            'destroy' => 'v3.vendors.clients.destroy'
        ]);
    Route::get('/{vendor}/clients/{client}/orders', [VendorClientController::class, 'getClientOrders'])->name('v3.vendors.client.orders');

    Route::apiResource('{vendor}/social-media', VendorSocialMediaController::class)->only(['store', 'update', 'destroy'])
        ->names([
            'store' => 'v3.vendors.social-media.store',
            'update' => 'v3.vendors.social-media.update',
            'destroy' => 'v3.vendors.social-media.destroy'
        ]);
    Route::apiResource('{vendor}/influencers', InfluencerController::class)->only(['index', 'store', 'update', 'destroy'])
        ->names([
            'index' => 'v3.vendors.influencers.index',
            'store' => 'v3.vendors.influencers.store',
            'update' => 'v3.vendors.influencers.update',
            'destroy' => 'v3.vendors.influencers.destroy'
        ]);
});
