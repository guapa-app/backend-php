<?php

use App\Http\Controllers\Api\V3\VendorController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api', 'as' => 'vendors.'], function () {
    Route::post('/',                                                        [VendorController::class, 'create'])->name('create');
    Route::match(['put', 'patch', 'post'], '/{id}',                         [VendorController::class, 'update'])->name('update');
});
