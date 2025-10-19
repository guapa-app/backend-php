<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\V3_1\CityController;

Route::get('/', [CityController::class, 'index'])->name('v3_1.cities.index');
