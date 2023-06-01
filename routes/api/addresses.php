<?php

use App\Http\Controllers\Api\AddressController as ApiAddressController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('/',                                         [ApiAddressController::class, 'index']);
	Route::get('/{id}',                                     [ApiAddressController::class, 'single']);
	Route::post('/',                                        [ApiAddressController::class, 'create']);
    Route::match(['put', 'patch', 'post'], '/{id}',[ApiAddressController::class, 'update']);
    Route::delete('/{id}',                                  [ApiAddressController::class, 'delete']);
});
