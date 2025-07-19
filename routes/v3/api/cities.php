<?php

use App\Http\Controllers\Api\V3\CityController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/',                  [CityController::class, 'index'])->name('v3.cities.index');
    Route::get('/{id}',              [CityController::class, 'show'])->name('v3.cities.show');
});
