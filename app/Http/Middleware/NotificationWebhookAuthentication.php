<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\InterServiceAuthenticationService;

/**
 * NotificationWebhookAuthentication Middleware
 * 
 * Authenticates incoming webhooks from the external notification service.
 * Validates HMAC signatures, timestamps, and API tokens.
 */
class NotificationWebhookAuthentication
{
    protected $authService;

    public function __construct(InterServiceAuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // 1. Validate required headers
            $this->validateHeaders($request);

            // 2. Verify API token
            $this->verifyApiToken($request);

            // 3. Verify HMAC signature
            $this->verifySignature($request);

            // 4. Validate timestamp (prevent replay attacks)
            $this->validateTimestamp($request);

            // Log successful authentication
            Log::info('Webhook authentication successful', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'endpoint' => $request->path(),
                'timestamp' => $request->header('X-Timestamp')
            ]);

            return $next($request);
        } catch (\Exception $e) {
            // Log authentication failure
            Log::warning('Webhook authentication failed', [
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
                'endpoint' => $request->path(),
                'headers' => $this->sanitizeHeaders($request->headers->all())
            ]);

            return response()->json([
                'error' => 'Authentication failed',
                'message' => 'Invalid webhook authentication'
            ], 401);
        }
    }

    /**
     * Validate required authentication headers
     *
     * @param Request $request
     * @throws \Exception
     */
    protected function validateHeaders(Request $request): void
    {
        $requiredHeaders = [
            'Authorization',
            'X-Timestamp',
            'X-Signature',
            'X-App-ID'
        ];

        $missing = [];
        foreach ($requiredHeaders as $header) {
            if (!$request->hasHeader($header)) {
                $missing[] = $header;
            }
        }

        if (!empty($missing)) {
            throw new \Exception('Missing required headers: ' . implode(', ', $missing));
        }
    }

    /**
     * Verify API token from Authorization header
     *
     * @param Request $request
     * @throws \Exception
     */
    protected function verifyApiToken(Request $request): void
    {
        $authHeader = $request->header('Authorization');

        if (!str_starts_with($authHeader, 'Bearer ')) {
            throw new \Exception('Invalid Authorization header format');
        }

        $token = substr($authHeader, 7); // Remove 'Bearer '
        $expectedToken = config('services.external_notification.token');

        if (empty($expectedToken)) {
            throw new \Exception('External notification token not configured');
        }

        if (!hash_equals($expectedToken, $token)) {
            throw new \Exception('Invalid API token');
        }
    }

    /**
     * Verify HMAC signature
     *
     * @param Request $request
     * @throws \Exception
     */
    protected function verifySignature(Request $request): void
    {
        $receivedSignature = $request->header('X-Signature');
        $timestamp = $request->header('X-Timestamp');
        $payload = $request->getContent();

        if (!$this->authService->verifyWebhookSignature($payload, $receivedSignature, $timestamp)) {
            throw new \Exception('Invalid signature');
        }
    }

    /**
     * Validate timestamp to prevent replay attacks
     *
     * @param Request $request
     * @throws \Exception
     */
    protected function validateTimestamp(Request $request): void
    {
        $timestamp = $request->header('X-Timestamp');

        if (!is_numeric($timestamp)) {
            throw new \Exception('Invalid timestamp format');
        }

        $requestTime = (int) $timestamp;
        $currentTime = now()->timestamp;
        $timeDifference = abs($currentTime - $requestTime);

        // Allow 5 minutes tolerance
        if ($timeDifference > 300) {
            throw new \Exception("Timestamp too old. Difference: {$timeDifference} seconds");
        }
    }

    /**
     * Sanitize headers for logging (remove sensitive data)
     *
     * @param array $headers
     * @return array
     */
    protected function sanitizeHeaders(array $headers): array
    {
        $sensitiveHeaders = ['authorization', 'x-signature'];
        $sanitized = [];

        foreach ($headers as $name => $values) {
            $lowerName = strtolower($name);
            if (in_array($lowerName, $sensitiveHeaders)) {
                $sanitized[$name] = ['***REDACTED***'];
            } else {
                $sanitized[$name] = $values;
            }
        }

        return $sanitized;
    }
}
