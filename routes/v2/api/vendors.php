<?php

use App\Http\Controllers\Api\V2\VendorController;
use Illuminate\Support\Facades\Route;

Route::get('/',                                                             [VendorController::class, 'index'])->name('v2.vendors.list');
Route::get('/{id}',                                                         [VendorController::class, 'single'])->name('v2.vendors.show');

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('/',                                                        [VendorController::class, 'create'])->name('v2.vendors.create');
    Route::match(['put', 'patch', 'post'], '/{id}',                [VendorController::class, 'update'])->name('v2.vendors.update');
    Route::post('/{id}/share',                                              [VendorController::class, 'share'])->name('v2.vendors.share');
});
