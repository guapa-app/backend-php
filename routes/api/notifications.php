<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('/', 'NotificationController@index');
	Route::get('/unread', 'NotificationController@unread');
	Route::get('/unread_count', 'NotificationController@unread_count');
	Route::patch('/mark_all_read', 'NotificationController@markAllAsRead');
	Route::put('/{id}/mark_read', 'NotificationController@markRead');
});
