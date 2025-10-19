<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:admin'], function() {
	Route::get('/', 'HistoryController@index');
	Route::get('/{id}', 'HistoryController@single');
	Route::post('/', 'HistoryController@create');
    Route::match(['put', 'patch', 'post'], '/{id}', 'HistoryController@update');
    Route::delete('/{id}', 'HistoryController@delete');
});