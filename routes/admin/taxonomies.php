<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:admin'], function() {
	Route::get('/', 'TaxonomyController@index');
	Route::get('/{id}', 'TaxonomyController@single');
	Route::post('/', 'TaxonomyController@create');
    Route::put('/{id}', 'TaxonomyController@update');
    Route::post('/{id}', 'TaxonomyController@update');
    Route::delete('/{id}', 'TaxonomyController@delete');
});
