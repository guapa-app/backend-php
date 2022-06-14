<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::post('/', 'OfferController@create');
    Route::match(['put', 'patch', 'post'], '/{id}', 'OfferController@update');
    Route::delete('/{id}', 'OfferController@delete');
});
