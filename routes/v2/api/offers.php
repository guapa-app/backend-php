<?php

use App\Http\Controllers\Api\OfferController as ApiOfferController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::post('/',                                         [ApiOfferController::class, 'create']);
    Route::match(['put', 'patch', 'post'], '/{id}', [ApiOfferController::class, 'update']);
    Route::delete('/{id}',                                   [ApiOfferController::class, 'delete']);
});
