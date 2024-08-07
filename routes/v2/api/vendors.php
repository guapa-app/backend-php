<?php

use App\Http\Controllers\Api\V2\VendorController;
use Illuminate\Support\Facades\Route;

Route::get('/',                                                             [VendorController::class, 'index'])->name('list');
Route::get('/{id}',                                                         [VendorController::class, 'single'])->name('single')->name('vendors.show');

Route::group(['middleware' => 'auth:api', 'as' => 'vendors.'], function () {
    Route::post('/',                                                        [VendorController::class, 'create'])->name('create');
    Route::match(['put', 'patch', 'post'], '/{id}',                [VendorController::class, 'update'])->name('update');
    Route::post('/{id}/share',                                              [VendorController::class, 'share'])->name('share');
});
