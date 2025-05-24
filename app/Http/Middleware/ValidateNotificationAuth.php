<?php

namespace App\Http\Middleware;

use App\Services\NotificationAuthService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValidateNotificationAuth
{
    protected $authService;

    public function __construct(NotificationAuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Extract headers
        $headers = $request->headers->all();

        // Get request payload
        $payload = $request->all();

        // Validate authentication
        if (!$this->authService->validateIncomingRequest($headers, $payload)) {
            Log::warning('Unauthorized notification request attempt', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'headers' => $this->sanitizeHeaders($headers)
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Unauthorized',
                'message' => 'Invalid authentication credentials'
            ], 401);
        }

        // Authentication successful, continue to controller
        return $next($request);
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
        $sanitized = $headers;

        foreach ($sensitiveHeaders as $header) {
            if (isset($sanitized[$header])) {
                $sanitized[$header] = ['***REDACTED***'];
            }
        }

        return $sanitized;
    }
}
