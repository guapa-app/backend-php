<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ExternalNotificationController extends Controller
{
    protected $authService;

    public function __construct(NotificationAuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Receive notification delivery status from external service
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function receiveStatus(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'delivery_id' => 'required|string',
            'status' => 'required|in:sent,delivered,failed,read',
            'module' => 'required|string',
            'recipient_id' => 'required|integer',
            'channel' => 'required|string',
            'timestamp' => 'required|date',
            'error_message' => 'nullable|string',
            'external_id' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        // Log the delivery status
        Log::info('Received notification delivery status', [
            'delivery_id' => $data['delivery_id'],
            'status' => $data['status'],
            'module' => $data['module'],
            'recipient_id' => $data['recipient_id'],
            'channel' => $data['channel'],
            'timestamp' => $data['timestamp']
        ]);

        // Here you could store delivery status in database if needed
        // For now, we just log and acknowledge

        return response()->json([
            'success' => true,
            'message' => 'Status received successfully',
            'delivery_id' => $data['delivery_id']
        ]);
    }

    /**
     * Receive webhook from external service
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function receiveWebhook(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'event_type' => 'required|string',
            'data' => 'required|array',
            'timestamp' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        // Log webhook event
        Log::info('Received webhook from external notification service', [
            'event_type' => $data['event_type'],
            'timestamp' => $data['timestamp'],
            'data_keys' => array_keys($data['data'])
        ]);

        // Process different webhook events
        switch ($data['event_type']) {
            case 'delivery_status':
                return $this->handleDeliveryStatus($data['data']);

            case 'user_interaction':
                return $this->handleUserInteraction($data['data']);

            case 'service_health':
                return $this->handleServiceHealth($data['data']);

            default:
                Log::warning('Unknown webhook event type', ['event_type' => $data['event_type']]);
                return response()->json([
                    'success' => true,
                    'message' => 'Event acknowledged but not processed'
                ]);
        }
    }

    /**
     * Handle delivery status webhook
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleDeliveryStatus(array $data): \Illuminate\Http\JsonResponse
    {
        Log::info('Processing delivery status webhook', $data);

        // Here you could update notification status in your database
        // Example: NotificationDelivery::updateStatus($data['delivery_id'], $data['status']);

        return response()->json([
            'success' => true,
            'message' => 'Delivery status processed'
        ]);
    }

    /**
     * Handle user interaction webhook (e.g., notification clicked)
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleUserInteraction(array $data): \Illuminate\Http\JsonResponse
    {
        Log::info('Processing user interaction webhook', $data);

        // Here you could track user engagement metrics
        // Example: NotificationMetrics::recordInteraction($data);

        return response()->json([
            'success' => true,
            'message' => 'User interaction recorded'
        ]);
    }

    /**
     * Handle service health webhook
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleServiceHealth(array $data): \Illuminate\Http\JsonResponse
    {
        Log::info('Received service health update', $data);

        // Here you could update service status monitoring
        // Example: ServiceHealth::updateStatus('external_notifications', $data['status']);

        return response()->json([
            'success' => true,
            'message' => 'Health status updated'
        ]);
    }

    /**
     * Test endpoint for external service
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function test(Request $request)
    {
        Log::info('External service test request received', [
            'timestamp' => now(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Authentication and connection successful',
            'timestamp' => now()->toISOString(),
            'app_id' => config('services.external_notification.app_id')
        ]);
    }
}
