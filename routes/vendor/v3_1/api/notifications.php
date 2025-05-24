<?php

use App\Http\Controllers\Api\ExternalNotificationController;
use App\Http\Controllers\Api\NotificationHealthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Notification API Routes (v3.1)
|--------------------------------------------------------------------------
|
| These routes handle notification system communications including:
| - External service webhook endpoints (secured with notification.auth)
| - Health monitoring and testing (secured with admin authentication)
| - System configuration and status checking
|
*/

// External notification service routes (secured with authentication)
// These endpoints receive callbacks and webhooks from the external notification service
Route::group([
    'prefix' => 'external-notifications',
    'middleware' => 'notification.auth'
], function () {
    // Receive delivery status updates from external service
    Route::post('/status', [ExternalNotificationController::class, 'receiveStatus'])
        ->name('external-notifications.status');

    // Receive general webhooks from external service
    Route::post('/webhook', [ExternalNotificationController::class, 'receiveWebhook'])
        ->name('external-notifications.webhook');

    // Test endpoint for external service authentication verification
    Route::post('/test', [ExternalNotificationController::class, 'test'])
        ->name('external-notifications.test');
});

// Notification system health checks (internal admin use)
// These endpoints are used for monitoring and testing the notification system
Route::group([
    'prefix' => 'notifications/health',
    'middleware' => 'auth:admin'
], function () {
    // Get overall notification system health status
    Route::get('/status', [NotificationHealthController::class, 'status'])
        ->name('notifications.health.status');

    // Test connection to external notification service
    Route::get('/test-connection', [NotificationHealthController::class, 'testConnection'])
        ->name('notifications.health.test-connection');

    // Validate notification system configuration
    Route::get('/validate-config', [NotificationHealthController::class, 'validateConfig'])
        ->name('notifications.health.validate-config');

    // Send test notification through the system
    Route::post('/send-test', [NotificationHealthController::class, 'sendTestNotification'])
        ->name('notifications.health.send-test');

    // Get authentication configuration info (for debugging)
    Route::get('/auth-info', [NotificationHealthController::class, 'authInfo'])
        ->name('notifications.health.auth-info');
});

// Notification management routes (admin use)
// Additional routes for notification system management
Route::group([
    'prefix' => 'notifications',
    'middleware' => 'auth:admin'
], function () {
    // Send single notification (for testing/manual sending)
    Route::post('/send', [\App\Http\Controllers\Api\NotificationController::class, 'send'])
        ->name('notifications.send');

    // Send batch notification (for campaigns)
    Route::post('/send-batch', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'module' => 'required|string',
            'title' => 'required|string',
            'summary' => 'required|string',
            'recipient_ids' => 'required|array',
            'recipient_ids.*' => 'integer',
            'data' => 'nullable|array'
        ]);

        $service = app(\App\Services\UnifiedNotificationService::class);

        $result = $service->sendToMultiple(
            module: $request->input('module'),
            title: $request->input('title'),
            summary: $request->input('summary'),
            recipientIds: $request->input('recipient_ids'),
            data: $request->input('data', [])
        );

        return response()->json([
            'success' => true,
            'message' => 'Batch notification processed',
            'results' => $result
        ]);
    })->name('notifications.send-batch');

    // Get notification statistics and metrics
    Route::get('/stats', function () {
        return response()->json([
            'success' => true,
            'stats' => [
                'service_configured' => !empty(config('services.external_notification.token')),
                'endpoint' => config('services.external_notification.endpoint'),
                'retry_attempts' => config('services.external_notification.retry_attempts'),
                'timeout' => config('services.external_notification.timeout'),
                'ssl_verification' => config('services.external_notification.verify_ssl'),
            ],
            'timestamp' => now()->toISOString()
        ]);
    })->name('notifications.stats');
});
