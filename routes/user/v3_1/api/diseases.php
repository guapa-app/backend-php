<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\V3_1\DiseaseController;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/', [DiseaseController::class, 'index']);
});