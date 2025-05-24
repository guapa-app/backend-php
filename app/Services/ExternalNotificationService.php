<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;

class ExternalNotificationService
{
    protected $authService;
    protected $endpoint;
    protected $timeout;
    protected $retryAttempts;
    protected $retryDelay;
    protected $verifySsl;

    public function __construct(NotificationAuthService $authService)
    {
        $this->authService = $authService;
        $this->endpoint = config('services.external_notification.endpoint');
        $this->timeout = config('services.external_notification.timeout', 30);
        $this->retryAttempts = config('services.external_notification.retry_attempts', 3);
        $this->retryDelay = config('services.external_notification.retry_delay', 1000);
        $this->verifySsl = config('services.external_notification.verify_ssl', true);
    }

    /**
     * Send notification to external service
     *
     * @param array $payload
     * @return bool
     */
    public function send(array $payload): bool
    {
        // Validate configuration first
        $configErrors = $this->authService->validateConfiguration();
        if (!empty($configErrors)) {
            Log::error('External notification service misconfigured', ['errors' => $configErrors]);
            return false;
        }

        return $this->sendWithRetry($payload);
    }

    /**
     * Send notification to multiple recipients
     *
     * @param array $payload
     * @return bool
     */
    public function sendBatch(array $payload): bool
    {
        // Validate configuration first
        $configErrors = $this->authService->validateConfiguration();
        if (!empty($configErrors)) {
            Log::error('External notification service misconfigured', ['errors' => $configErrors]);
            return false;
        }

        // Use batch endpoint
        $batchEndpoint = str_replace('/notifications', '/notifications/batch', $this->endpoint);
        return $this->sendWithRetry($payload, $batchEndpoint);
    }

    /**
     * Send with retry logic
     *
     * @param array $payload
     * @param string|null $customEndpoint
     * @return bool
     */
    protected function sendWithRetry(array $payload, ?string $customEndpoint = null): bool
    {
        $endpoint = $customEndpoint ?? $this->endpoint;
        $lastException = null;

        for ($attempt = 1; $attempt <= $this->retryAttempts; $attempt++) {
            try {
                Log::info('Sending notification to external service', [
                    'attempt' => $attempt,
                    'endpoint' => $endpoint,
                    'module' => $payload['module'] ?? 'unknown',
                    'recipient_id' => $payload['recipient_id'] ?? $payload['recipient_ids'] ?? 'unknown'
                ]);

                $response = $this->makeRequest($endpoint, $payload);

                if ($response->successful()) {
                    Log::info('Notification sent successfully', [
                        'attempt' => $attempt,
                        'response' => $response->json()
                    ]);
                    return true;
                }

                // Log non-successful responses
                Log::warning('External service returned non-successful response', [
                    'attempt' => $attempt,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                // Don't retry on client errors (4xx)
                if ($response->clientError()) {
                    Log::error('Client error, not retrying', [
                        'status' => $response->status(),
                        'response' => $response->body()
                    ]);
                    return false;
                }
            } catch (RequestException $e) {
                $lastException = $e;
                Log::warning('Request exception on attempt ' . $attempt, [
                    'error' => $e->getMessage(),
                    'attempt' => $attempt
                ]);
            }

            // Wait before retry (except on last attempt)
            if ($attempt < $this->retryAttempts) {
                usleep($this->retryDelay * 1000); // Convert to microseconds
            }
        }

        // All attempts failed
        Log::error('Failed to send notification after all retry attempts', [
            'attempts' => $this->retryAttempts,
            'last_error' => $lastException ? $lastException->getMessage() : 'Unknown error',
            'payload' => $payload
        ]);

        return false;
    }

    /**
     * Make authenticated HTTP request
     *
     * @param string $endpoint
     * @param array $payload
     * @return \Illuminate\Http\Client\Response
     */
    protected function makeRequest(string $endpoint, array $payload)
    {
        // Generate authentication headers
        $headers = $this->authService->generateAuthHeaders($payload);

        // Create HTTP client with configuration
        $http = Http::timeout($this->timeout);

        if (!$this->verifySsl) {
            $http = $http->withoutVerifying();
        }

        // Add headers and send request
        return $http->withHeaders($headers)->post($endpoint, $payload);
    }

    /**
     * Test connection to external service
     *
     * @return array
     */
    public function testConnection(): array
    {
        try {
            $testPayload = [
                'module' => 'test-connection',
                'title' => 'Connection Test',
                'summary' => 'Testing connection to external notification service',
                'recipient_id' => 0,
                'channels' => ['test'],
                'data' => ['test' => true]
            ];

            Log::info('Testing connection to external notification service');

            $response = $this->makeRequest($this->endpoint, $testPayload);

            return [
                'success' => $response->successful(),
                'status_code' => $response->status(),
                'response_time' => $response->handlerStats()['total_time'] ?? 'unknown',
                'response_body' => $response->json() ?? $response->body(),
                'endpoint' => $this->endpoint
            ];
        } catch (\Exception $e) {
            Log::error('Connection test failed', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'endpoint' => $this->endpoint
            ];
        }
    }

    /**
     * Get service health status
     *
     * @return array
     */
    public function getHealthStatus(): array
    {
        $configErrors = $this->authService->validateConfiguration();

        return [
            'configured' => empty($configErrors),
            'configuration_errors' => $configErrors,
            'endpoint' => $this->endpoint,
            'timeout' => $this->timeout,
            'retry_attempts' => $this->retryAttempts,
            'ssl_verification' => $this->verifySsl
        ];
    }
}
