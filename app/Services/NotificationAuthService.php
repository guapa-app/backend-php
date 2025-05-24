<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NotificationAuthService
{
    protected $appId;
    protected $secretKey;
    protected $token;

    public function __construct()
    {
        $this->appId = config('services.external_notification.app_id');
        $this->secretKey = config('services.external_notification.secret_key');
        $this->token = config('services.external_notification.token');
    }

    /**
     * Generate authentication headers for outgoing requests
     *
     * @param array $payload The request payload
     * @return array Headers array
     */
    public function generateAuthHeaders(array $payload = []): array
    {
        $timestamp = now()->timestamp;
        $nonce = Str::random(32);

        // Create signature
        $signature = $this->generateSignature($payload, $timestamp, $nonce);

        return [
            'Authorization' => 'Bearer ' . $this->token,
            'X-App-ID' => $this->appId,
            'X-Timestamp' => $timestamp,
            'X-Nonce' => $nonce,
            'X-Signature' => $signature,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Validate incoming request authentication
     *
     * @param array $headers
     * @param array $payload
     * @return bool
     */
    public function validateIncomingRequest(array $headers, array $payload = []): bool
    {
        try {
            // Extract headers (handle case-insensitive)
            $headers = array_change_key_case($headers, CASE_LOWER);

            $token = $this->extractBearerToken($headers['authorization'] ?? '');
            $appId = $headers['x-app-id'] ?? '';
            $timestamp = $headers['x-timestamp'] ?? '';
            $nonce = $headers['x-nonce'] ?? '';
            $signature = $headers['x-signature'] ?? '';

            // Validate required fields
            if (empty($token) || empty($appId) || empty($timestamp) || empty($nonce) || empty($signature)) {
                Log::warning('Missing authentication headers', compact('token', 'appId', 'timestamp', 'nonce', 'signature'));
                return false;
            }

            // Validate token
            if ($token !== $this->token) {
                Log::warning('Invalid bearer token provided');
                return false;
            }

            // Validate app ID
            if ($appId !== $this->appId) {
                Log::warning('Invalid app ID provided', ['provided' => $appId, 'expected' => $this->appId]);
                return false;
            }

            // Validate timestamp (allow 5 minutes tolerance)
            $requestTime = Carbon::createFromTimestamp($timestamp);
            if ($requestTime->diffInMinutes(now()) > 5) {
                Log::warning('Request timestamp too old', ['timestamp' => $timestamp, 'diff' => $requestTime->diffInMinutes(now())]);
                return false;
            }

            // Validate signature
            $expectedSignature = $this->generateSignature($payload, $timestamp, $nonce);
            if (!hash_equals($expectedSignature, $signature)) {
                Log::warning('Invalid signature provided');
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Authentication validation error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Generate request signature
     *
     * @param array $payload
     * @param int $timestamp
     * @param string $nonce
     * @return string
     */
    protected function generateSignature(array $payload, int $timestamp, string $nonce): string
    {
        // Create string to sign: HTTP_METHOD|URI|PAYLOAD|TIMESTAMP|NONCE|APP_ID
        $stringToSign = implode('|', [
            'POST',
            '/api/notifications',
            json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            $timestamp,
            $nonce,
            $this->appId
        ]);

        return hash_hmac('sha256', $stringToSign, $this->secretKey);
    }

    /**
     * Extract bearer token from authorization header
     *
     * @param string $authHeader
     * @return string|null
     */
    protected function extractBearerToken(string $authHeader): ?string
    {
        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Generate a new API token
     *
     * @return string
     */
    public function generateApiToken(): string
    {
        return Str::random(64);
    }

    /**
     * Generate a new secret key
     *
     * @return string
     */
    public function generateSecretKey(): string
    {
        return Str::random(128);
    }

    /**
     * Validate configuration
     *
     * @return array
     */
    public function validateConfiguration(): array
    {
        $errors = [];

        if (empty($this->token)) {
            $errors[] = 'EXTERNAL_NOTIFICATION_TOKEN is not configured';
        }

        if (empty($this->secretKey)) {
            $errors[] = 'EXTERNAL_NOTIFICATION_SECRET_KEY is not configured';
        }

        if (empty($this->appId)) {
            $errors[] = 'EXTERNAL_NOTIFICATION_APP_ID is not configured';
        }

        return $errors;
    }
}
