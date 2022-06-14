<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:admin'], function() {
	Route::get('/', 'ReviewController@index');
	Route::get('/{id}', 'ReviewController@single');
	Route::post('/', 'ReviewController@create');
    Route::match(['put', 'patch', 'post'], '/{id}', 'ReviewController@update');
    Route::delete('/{id}', 'ReviewController@delete');
});