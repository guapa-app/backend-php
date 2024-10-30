<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAppVersion
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $clientVersion = $request->header('App-Version');
        $minSupportedVersion = Setting::getMinSupportedVersion();

        if ($minSupportedVersion && version_compare($clientVersion, $minSupportedVersion, '<')) {
            return response()->json([
                'message' => __('api.app_out_of_date'),
            ], Response::HTTP_UPGRADE_REQUIRED);
        }

        return $next($request);
    }
}
