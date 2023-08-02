<?php

use App\Http\Controllers\Api\V2\NotificationController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('/',                                         [NotificationController::class, 'index']);
	Route::get('/unread',                                   [NotificationController::class, 'unread']);
	Route::get('/unread_count',                             [NotificationController::class, 'unread_count']);
	Route::patch('/mark_all_read',                          [NotificationController::class, 'markAllAsRead']);
	Route::put('/{id}/mark_read',                           [NotificationController::class, 'markRead']);
});
