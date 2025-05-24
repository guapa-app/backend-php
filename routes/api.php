// External notification service routes (secured with authentication)
Route::group(['prefix' => 'external-notifications', 'middleware' => 'notification.auth'], function () {
    Route::post('/status', [App\Http\Controllers\Api\ExternalNotificationController::class, 'receiveStatus']);
    Route::post('/webhook', [App\Http\Controllers\Api\ExternalNotificationController::class, 'receiveWebhook']);
    Route::post('/test', [App\Http\Controllers\Api\ExternalNotificationController::class, 'test']);
}); 