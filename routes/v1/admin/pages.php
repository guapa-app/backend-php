<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:admin'], function() {
	Route::get('/', 'PageController@index');
	Route::get('/{id}', 'PageController@single');
	Route::post('/', 'PageController@create');
    Route::put('/{id}', 'PageController@update');
    Route::post('/{id}', 'PageController@update');
    Route::delete('/{id}', 'PageController@delete');
});
