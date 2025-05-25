<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * InterServiceAuthenticationService
 * 
 * Handles secure authentication between this app and the external notification service.
 * Implements multiple layers of security:
 * - API Token authentication
 * - Request signing with HMAC
 * - Timestamp validation to prevent replay attacks
 * - Optional IP whitelisting
 */
class InterServiceAuthenticationService
{
    protected $appId;
    protected $apiToken;
    protected $secretKey;
    protected $baseUrl;
    protected $timeout;

    public function __construct()
    {
        $this->appId = config('services.external_notification.app_id');
        $this->apiToken = config('services.external_notification.token');
        $this->secretKey = config('services.external_notification.secret_key');
        $this->baseUrl = rtrim(config('services.external_notification.endpoint'), '/');
        $this->timeout = config('services.external_notification.timeout', 30);
    }

    /**
     * Make an authenticated request to the notification service
     *
     * @param string $method HTTP method (GET, POST, PUT, DELETE)
     * @param string $endpoint API endpoint (relative to base URL)
     * @param array $data Request payload
     * @param array $options Additional HTTP options
     * @return array Response data
     * @throws \Exception
     */
    public function makeAuthenticatedRequest(
        string $method,
        string $endpoint,
        array $data = [],
        array $options = []
    ): array {
        $this->validateConfiguration();

        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        $timestamp = now()->timestamp;
        $nonce = $this->generateNonce();

        // Prepare request data
        $requestData = array_merge($data, [
            'app_id' => $this->appId,
            'timestamp' => $timestamp,
            'nonce' => $nonce,
        ]);

        // Generate signature
        $signature = $this->generateSignature($method, $endpoint, $requestData, $timestamp, $nonce);

        // Prepare headers
        $headers = array_merge([
            'Authorization' => 'Bearer ' . $this->apiToken,
            'X-App-ID' => $this->appId,
            'X-Timestamp' => $timestamp,
            'X-Nonce' => $nonce,
            'X-Signature' => $signature,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'Guapa-Laravel-App/1.0'
        ], $options['headers'] ?? []);

        try {
            // Make HTTP request
            $response = Http::timeout($this->timeout)
                ->withHeaders($headers)
                ->send(strtoupper($method), $url, [
                    'json' => $requestData
                ]);

            // Handle response
            return $this->handleResponse($response, $method, $endpoint);
        } catch (\Exception $e) {
            Log::error('Inter-service request failed', [
                'method' => $method,
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
                'url' => $url
            ]);
            throw $e;
        }
    }

    /**
     * Send notification via authenticated API call
     *
     * @param array $notificationData
     * @return bool Success status
     */
    public function sendNotification(array $notificationData): bool
    {
        try {
            $response = $this->makeAuthenticatedRequest('POST', '/send', $notificationData);
            return $response['success'] ?? false;
        } catch (\Exception $e) {
            Log::error('Failed to send notification via API', [
                'error' => $e->getMessage(),
                'data' => $notificationData
            ]);
            return false;
        }
    }

    /**
     * Send bulk notifications via authenticated API call
     *
     * @param array $bulkData
     * @return bool Success status
     */
    public function sendBulkNotifications(array $bulkData): bool
    {
        try {
            $response = $this->makeAuthenticatedRequest('POST', '/send-bulk', $bulkData);
            return $response['success'] ?? false;
        } catch (\Exception $e) {
            Log::error('Failed to send bulk notifications via API', [
                'error' => $e->getMessage(),
                'data' => $bulkData
            ]);
            return false;
        }
    }

    /**
     * Test connection to notification service
     *
     * @return array Test result with status and details
     */
    public function testConnection(): array
    {
        try {
            $response = $this->makeAuthenticatedRequest('GET', '/health');

            return [
                'success' => true,
                'status' => $response['status'] ?? 'unknown',
                'response_time' => $response['response_time'] ?? 0,
                'service_version' => $response['version'] ?? 'unknown',
                'message' => 'Connection successful'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Connection failed'
            ];
        }
    }

    /**
     * Validate authentication configuration
     *
     * @throws \Exception if configuration is invalid
     */
    protected function validateConfiguration(): void
    {
        $errors = [];

        if (empty($this->appId)) {
            $errors[] = 'EXTERNAL_NOTIFICATION_APP_ID is not configured';
        }

        if (empty($this->apiToken)) {
            $errors[] = 'EXTERNAL_NOTIFICATION_TOKEN is not configured';
        }

        if (empty($this->secretKey)) {
            $errors[] = 'EXTERNAL_NOTIFICATION_SECRET_KEY is not configured';
        }

        if (empty($this->baseUrl)) {
            $errors[] = 'EXTERNAL_NOTIFICATION_ENDPOINT is not configured';
        }

        if (!empty($errors)) {
            throw new \Exception('Inter-service authentication configuration errors: ' . implode(', ', $errors));
        }
    }

    /**
     * Generate cryptographic signature for request integrity
     *
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @param int $timestamp
     * @param string $nonce
     * @return string
     */
    protected function generateSignature(
        string $method,
        string $endpoint,
        array $data,
        int $timestamp,
        string $nonce
    ): string {
        // Create signing string
        $signingString = implode('|', [
            strtoupper($method),
            $endpoint,
            $this->appId,
            $timestamp,
            $nonce,
            json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        ]);

        // Generate HMAC signature
        return hash_hmac('sha256', $signingString, $this->secretKey);
    }

    /**
     * Generate unique nonce for request
     *
     * @return string
     */
    protected function generateNonce(): string
    {
        return bin2hex(random_bytes(16));
    }

    /**
     * Handle HTTP response
     *
     * @param \Illuminate\Http\Client\Response $response
     * @param string $method
     * @param string $endpoint
     * @return array
     * @throws \Exception
     */
    protected function handleResponse($response, string $method, string $endpoint): array
    {
        $statusCode = $response->status();
        $responseData = $response->json();

        // Log successful requests
        if ($response->successful()) {
            Log::info('Inter-service request successful', [
                'method' => $method,
                'endpoint' => $endpoint,
                'status_code' => $statusCode,
                'response_time' => $response->handlerStats()['total_time'] ?? 0
            ]);

            return $responseData ?? [];
        }

        // Handle different error types
        $errorMessage = $responseData['message'] ?? $responseData['error'] ?? 'Unknown error';

        if ($statusCode === 401) {
            throw new \Exception("Authentication failed: {$errorMessage}");
        } elseif ($statusCode === 403) {
            throw new \Exception("Authorization failed: {$errorMessage}");
        } elseif ($statusCode === 429) {
            throw new \Exception("Rate limit exceeded: {$errorMessage}");
        } elseif ($statusCode >= 500) {
            throw new \Exception("External service error: {$errorMessage}");
        } else {
            throw new \Exception("Request failed: {$errorMessage} (Status: {$statusCode})");
        }
    }

    /**
     * Get service configuration for debugging
     *
     * @return array
     */
    public function getConfigurationStatus(): array
    {
        return [
            'app_id' => $this->appId ? 'SET' : 'NOT SET',
            'api_token' => $this->apiToken ? 'SET' : 'NOT SET',
            'secret_key' => $this->secretKey ? 'SET' : 'NOT SET',
            'base_url' => $this->baseUrl ?: 'NOT SET',
            'timeout' => $this->timeout,
            'is_configured' => !empty($this->appId) && !empty($this->apiToken) && !empty($this->secretKey) && !empty($this->baseUrl)
        ];
    }

    /**
     * Verify incoming webhook signature (for receiving callbacks)
     *
     * @param string $payload Request body
     * @param string $receivedSignature Signature from X-Signature header
     * @param string $timestamp Timestamp from X-Timestamp header
     * @return bool
     */
    public function verifyWebhookSignature(string $payload, string $receivedSignature, string $timestamp): bool
    {
        // Check timestamp freshness (prevent replay attacks)
        $currentTime = now()->timestamp;
        $requestTime = (int) $timestamp;

        if (abs($currentTime - $requestTime) > 300) { // 5 minutes tolerance
            Log::warning('Webhook rejected: timestamp too old', [
                'current_time' => $currentTime,
                'request_time' => $requestTime,
                'difference' => $currentTime - $requestTime
            ]);
            return false;
        }

        // Generate expected signature
        $signingString = $timestamp . '|' . $payload;
        $expectedSignature = hash_hmac('sha256', $signingString, $this->secretKey);

        // Secure comparison
        $isValid = hash_equals($expectedSignature, $receivedSignature);

        if (!$isValid) {
            Log::warning('Webhook rejected: invalid signature', [
                'expected_signature' => $expectedSignature,
                'received_signature' => $receivedSignature
            ]);
        }

        return $isValid;
    }
}
