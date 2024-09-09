<?php

use App\Http\Controllers\Api\MessagingController as ApiMessagingController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    // Get current user conversations
    Route::get('/conversations', [ApiMessagingController::class, 'conversations']);
    // Get current user conversation messages
    Route::get('/messages', [ApiMessagingController::class, 'messages']);
    // Mark conversation as read
    Route::patch('/conversations/{id}/mark_as_read', [ApiMessagingController::class, 'markConversationAsRead']);
    // Send message
    Route::post('/', [ApiMessagingController::class, 'create']);
});
