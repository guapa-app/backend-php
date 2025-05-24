<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ExternalNotificationService;
use App\Services\NotificationAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationHealthController extends Controller
{
    protected $externalService;
    protected $authService;

    public function __construct(
        ExternalNotificationService $externalService,
        NotificationAuthService $authService
    ) {
        $this->externalService = $externalService;
        $this->authService = $authService;
    }

    /**
     * Get notification system health status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function status()
    {
        try {
            // Get service health status
            $serviceHealth = $this->externalService->getHealthStatus();

            // Test connection to external service
            $connectionTest = $this->externalService->testConnection();

            // Overall health assessment
            $isHealthy = $serviceHealth['configured'] && $connectionTest['success'];

            return response()->json([
                'success' => true,
                'healthy' => $isHealthy,
                'timestamp' => now()->toISOString(),
                'service_status' => $serviceHealth,
                'connection_test' => $connectionTest,
                'system_info' => [
                    'app_name' => config('app.name'),
                    'app_env' => config('app.env'),
                    'notification_service_configured' => $serviceHealth['configured']
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Health check failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'healthy' => false,
                'error' => 'Health check failed',
                'message' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Test connection to external notification service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testConnection()
    {
        try {
            $result = $this->externalService->testConnection();

            return response()->json([
                'success' => $result['success'],
                'connection_test' => $result,
                'timestamp' => now()->toISOString()
            ], $result['success'] ? 200 : 503);
        } catch (\Exception $e) {
            Log::error('Connection test failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'error' => 'Connection test failed',
                'message' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Validate configuration
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateConfig()
    {
        try {
            $configErrors = $this->authService->validateConfiguration();
            $isValid = empty($configErrors);

            return response()->json([
                'success' => true,
                'valid' => $isValid,
                'configuration_errors' => $configErrors,
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            Log::error('Configuration validation failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'error' => 'Configuration validation failed',
                'message' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Send test notification
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendTestNotification(Request $request)
    {
        try {
            $request->validate([
                'recipient_id' => 'required|integer',
                'module' => 'string|nullable'
            ]);

            $testModule = $request->input('module', 'test-notification');
            $recipientId = $request->input('recipient_id');

            // Test configuration first
            $configErrors = $this->authService->validateConfiguration();
            if (!empty($configErrors)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Configuration invalid',
                    'configuration_errors' => $configErrors
                ], 400);
            }

            // Send test notification
            $result = $this->externalService->send([
                'module' => $testModule,
                'title' => 'Test Notification',
                'summary' => 'This is a test notification to verify the system is working',
                'recipient_id' => $recipientId,
                'channels' => ['test'],
                'data' => [
                    'test' => true,
                    'timestamp' => now()->toISOString(),
                    'health_check' => true
                ]
            ]);

            return response()->json([
                'success' => $result,
                'message' => $result ? 'Test notification sent successfully' : 'Failed to send test notification',
                'test_data' => [
                    'module' => $testModule,
                    'recipient_id' => $recipientId,
                    'timestamp' => now()->toISOString()
                ]
            ], $result ? 200 : 500);
        } catch (\Exception $e) {
            Log::error('Test notification failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'error' => 'Test notification failed',
                'message' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Get authentication info (for debugging)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authInfo()
    {
        try {
            $configErrors = $this->authService->validateConfiguration();

            return response()->json([
                'success' => true,
                'authentication_configured' => empty($configErrors),
                'configuration_errors' => $configErrors,
                'app_id' => config('services.external_notification.app_id'),
                'endpoint' => config('services.external_notification.endpoint'),
                'timeout' => config('services.external_notification.timeout'),
                'retry_attempts' => config('services.external_notification.retry_attempts'),
                'ssl_verification' => config('services.external_notification.verify_ssl'),
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            Log::error('Auth info retrieval failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve auth info',
                'message' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }
}
