<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Vendor\V3_1\CityController;

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/', [CityController::class, 'index'])->name('v3_1.vendor.cities.index');
    Route::get('/{id}', [CityController::class, 'show'])->name('v3_1.vendor.cities.show');
});
