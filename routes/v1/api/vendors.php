<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\VendorController;

Route::get('/',                                                             [VendorController::class, 'index'])->name('v1.vendors.list');
Route::get('/{id}',                                                         [VendorController::class, 'single'])->name('v1.vendors.show');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/',                                                        [VendorController::class, 'create'])->name('v1.vendors.create');
    Route::match(['put', 'patch', 'post'], '/{id}',                [VendorController::class, 'update'])->name('v1.vendors.update');
    Route::post('/{id}/share',                                              [VendorController::class, 'share'])->name('v1.vendors.share');
});
