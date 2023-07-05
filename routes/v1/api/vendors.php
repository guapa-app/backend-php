<?php

use App\Http\Controllers\Api\VendorController as ApiVendorController;
use Illuminate\Support\Facades\Route;

Route::get('/',                                                             [ApiVendorController::class, 'index'])->name('list');
Route::get('/{id}',                                                         [ApiVendorController::class, 'single'])->name('single');

Route::group(['middleware' => 'auth:api', 'as' => 'vendors.'], function () {
    Route::post('/',                                                        [ApiVendorController::class, 'create'])->name('create');
    Route::match(['put', 'patch', 'post'], '/{id}',                [ApiVendorController::class, 'update'])->name('update');
    Route::post('/{id}/share',                                              [ApiVendorController::class, 'share'])->name('share');
});
