<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:admin'], function() {
	Route::get('/', 'OfferController@index');
	Route::get('/{id}', 'OfferController@single');
	Route::post('/', 'OfferController@create');
    Route::put('/{id}', 'OfferController@update');
    Route::delete('/{id}', 'OfferController@delete');
});
