<?php

use App\Http\Controllers\Api\NotificationController as ApiNotificationController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('/',                                         [ApiNotificationController::class, 'index']);
	Route::get('/unread',                                   [ApiNotificationController::class, 'unread']);
	Route::get('/unread_count',                             [ApiNotificationController::class, 'unread_count']);
	Route::patch('/mark_all_read',                          [ApiNotificationController::class, 'markAllAsRead']);
	Route::put('/{id}/mark_read',                           [ApiNotificationController::class, 'markRead']);
});
