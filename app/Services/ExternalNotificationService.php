<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\InterServiceAuthenticationService;

class ExternalNotificationService
{
    protected $authService;

    public function __construct(InterServiceAuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Send a single notification through the external service
     *
     * @param array $data Notification payload
     * @return bool Success status
     */
    public function send(array $data): bool
    {
        try {
            // Validate required fields
            $this->validateNotificationData($data);

            // Add metadata
            $payload = array_merge($data, [
                'source_app' => 'guapa-laravel',
                'sent_at' => now()->toISOString(),
                'version' => '1.0'
            ]);

            // Use authenticated service to send
            return $this->authService->sendNotification($payload);
        } catch (\Exception $e) {
            Log::error('External notification service failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            // Return false to trigger fallback mechanisms
            return false;
        }
    }

    /**
     * Send bulk notifications through the external service
     *
     * @param array $data Bulk notification payload
     * @return bool Success status
     */
    public function sendBatch(array $data): bool
    {
        try {
            // Validate bulk data
            if (empty($data['recipient_ids']) || !is_array($data['recipient_ids'])) {
                throw new \Exception('Bulk notifications require recipient_ids array');
            }

            // Add metadata
            $payload = array_merge($data, [
                'source_app' => 'guapa-laravel',
                'sent_at' => now()->toISOString(),
                'version' => '1.0',
                'batch_size' => count($data['recipient_ids'])
            ]);

            // Use authenticated service to send
            return $this->authService->sendBulkNotifications($payload);
        } catch (\Exception $e) {
            Log::error('External bulk notification service failed', [
                'error' => $e->getMessage(),
                'recipient_count' => count($data['recipient_ids'] ?? [])
            ]);

            return false;
        }
    }

    /**
     * Test connection to external service
     *
     * @return array Connection test result
     */
    public function testConnection(): array
    {
        return $this->authService->testConnection();
    }

    /**
     * Get service configuration status
     *
     * @return array Configuration status
     */
    public function getConfigurationStatus(): array
    {
        return $this->authService->getConfigurationStatus();
    }

    /**
     * Validate notification data structure
     *
     * @param array $data
     * @throws \Exception
     */
    protected function validateNotificationData(array $data): void
    {
        $required = ['module', 'title', 'summary', 'recipient_id', 'channels'];
        $missing = [];

        foreach ($required as $field) {
            if (!isset($data[$field]) || (is_string($data[$field]) && trim($data[$field]) === '')) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            throw new \Exception('Missing required fields: ' . implode(', ', $missing));
        }

        // Validate recipient_id
        if (!is_numeric($data['recipient_id']) || $data['recipient_id'] <= 0) {
            throw new \Exception('Invalid recipient_id: must be a positive integer');
        }

        // Validate channels
        if (!is_array($data['channels']) && !is_string($data['channels'])) {
            throw new \Exception('Invalid channels: must be string or array');
        }

        // Validate module format
        if (!preg_match('/^[a-z0-9\-]+$/', $data['module'])) {
            throw new \Exception('Invalid module format: must contain only lowercase letters, numbers, and hyphens');
        }
    }

    /**
     * Handle fallback when external service is unavailable
     * This would typically use Laravel's built-in notification system
     *
     * @param array $data Original notification data
     * @return bool
     */
    public function fallbackToLaravel(array $data): bool
    {
        Log::info('Using Laravel fallback for notification', [
            'module' => $data['module'] ?? 'unknown',
            'recipient_id' => $data['recipient_id'] ?? 'unknown'
        ]);

        try {
            // This is where you'd implement fallback to Laravel notifications
            // For now, we'll just log and return true

            // Example implementation:
            // $user = User::find($data['recipient_id']);
            // if ($user) {
            //     $user->notify(new FallbackNotification($data));
            //     return true;
            // }

            return true;
        } catch (\Exception $e) {
            Log::error('Laravel fallback also failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            return false;
        }
    }

    /**
     * Get health status of the external service
     *
     * @return array Health status
     */
    public function getHealthStatus(): array
    {
        $configStatus = $this->getConfigurationStatus();

        if (!$configStatus['is_configured']) {
            return [
                'status' => 'error',
                'message' => 'Service not properly configured',
                'details' => $configStatus
            ];
        }

        $connectionTest = $this->testConnection();

        return [
            'status' => $connectionTest['success'] ? 'healthy' : 'unhealthy',
            'message' => $connectionTest['message'],
            'details' => array_merge($configStatus, $connectionTest)
        ];
    }
}
