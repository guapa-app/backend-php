<?php

use App\Http\Controllers\Api\User\V3_1\VendorClientController;
use App\Http\Controllers\Api\User\V3_1\VendorController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api', 'as' => 'vendors.'], function () {
    Route::post('/', [VendorController::class, 'create'])->name('create');
    Route::match(['put', 'patch', 'post'], '/{id}', [VendorController::class, 'update'])->name('update');

    Route::apiResource('{vendor}/clients', VendorClientController::class)->only(['index', 'store', 'destroy']);
    Route::get('/{vendor}/clients/{client}/orders',
        [VendorClientController::class, 'getClientOrders'])->name('client.orders');
});
