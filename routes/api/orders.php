<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('/', 'OrderController@index');
	Route::get('/{id}', 'OrderController@single');
	Route::post('/', 'OrderController@create');
    Route::post('{id}/print-pdf', 'OrderController@printPDF');
    Route::match(['put', 'patch', 'post'], '/{id}', 'OrderController@update');
});
