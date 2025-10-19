<?php

use App\Http\Controllers\Api\V1\OfferController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('/',                                         [OfferController::class, 'create']);
    Route::match(['put', 'patch', 'post'], '/{id}', [OfferController::class, 'update']);
    Route::delete('/{id}',                                   [OfferController::class, 'delete']);
});
