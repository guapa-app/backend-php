<?php

use App\Http\Controllers\Api\V3\CityController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth:api', 'as' => 'city.'], function () {
    Route::get('/',                  [CityController::class, 'index'])->name('index');
});
