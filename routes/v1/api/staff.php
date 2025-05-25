<?php

use App\Http\Controllers\Api\V1\StaffController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('/',                                         [StaffController::class, 'index']);
	Route::post('/',                                        [StaffController::class, 'create']);
	Route::match(['put', 'patch', 'post'], '/{id}',[StaffController::class, 'update']);
	Route::delete('/{userId}/{vendorId}',                   [StaffController::class, 'delete']);
});
