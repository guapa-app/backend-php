<?php

use App\Http\Controllers\Api\FavoriteController as ApiFavoriteController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('/',                                         [ApiFavoriteController::class, 'index']);
	Route::post('/',                                        [ApiFavoriteController::class, 'create']);
	Route::delete('/{type}/{id}',                           [ApiFavoriteController::class, 'delete']);
});
