<?php

use App\Http\Controllers\Api\User\V3_1\VendorController;
use App\Http\Controllers\Api\Vendor\V3_1\VendorSocialMediaController;
use App\Http\Controllers\Api\User\V3_1\ConsultationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [VendorController::class, 'index'])->name('list');
Route::get('/{id}', [VendorController::class, 'single'])->name('vendors.show');

// get available time slots for a vendor
Route::get('/{vendor}/available-times/{date}', [ConsultationController::class, 'getAvailableTimeSlots'])->name('vendors.available-time-slots');

Route::group(['middleware' => 'auth:api', 'as' => 'vendors.'], function () {
    Route::post('/', [VendorController::class, 'create'])->name('create');
    Route::match(['put', 'patch', 'post'], '/{id}', [VendorController::class, 'update'])->name('update');

    Route::apiResource('{vendor}/social-media', VendorSocialMediaController::class)->only([
        'store', 'update', 'destroy'
    ]);
});
