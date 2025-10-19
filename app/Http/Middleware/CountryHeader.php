<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Country;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CountryHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $countryId = $request->header('X-Country-ID');

        if (!$countryId) {
            $countryId = 1;
        }

        // Verify country exists and is active
        $country = Country::where('id', $countryId)
            ->where('active', true)
            ->first();

        if (!$country) {
            return response()->json([
                'error' => 'Invalid country',
                'message' => 'The provided country ID is invalid or inactive'
            ], 404);
        }

        // Store country in request for controllers
        $request->merge(['country' => $country]);

        return $next($request);
    }
}
