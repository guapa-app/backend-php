<?php

use App\Http\Controllers\Api\User\V3_1\AddressController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/', [AddressController::class, 'index']);
    Route::get('/{id}', [AddressController::class, 'single']);
    Route::post('/', [AddressController::class, 'create']);
    Route::match(['put', 'patch', 'post'], '/{id}', [AddressController::class, 'update']);
    Route::delete('/{id}', [AddressController::class, 'delete']);
});
