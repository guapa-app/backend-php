<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	// Get current user conversations
	Route::get('/conversations', 'MessagingController@conversations');
	// Get current user conversation messages
	Route::get('/messages', 'MessagingController@messages');
	// Mark conversation as read
	Route::patch('/conversations/{id}/mark_as_read', 'MessagingController@markConversationAsRead');
	// Send message
	Route::post('/', 'MessagingController@create');
	// Update offer status
	// Route::put('/messages/{id}', 'MessagingController@updateOfferStatus');
});
