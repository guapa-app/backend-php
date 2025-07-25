<?php

namespace App\Http\Controllers\Api\V3;

use App\Http\Controllers\ShareLinkController as BaseShareLinkController;
use App\Services\ShareLinkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShareLinkController extends BaseShareLinkController
{
    /**
     * Get share link data for mobile app integration
     * Returns different data structure based on platform requirements
     *
     * @param string $identifier
     * @return JsonResponse
     */
    public function getAppLinkData(string $identifier): JsonResponse
    {
        try {
            // Find the share link by identifier without logging the click
            $shareLink = \App\Models\ShareLink::where('identifier', $identifier)->firstOrFail();
            
            // For Android app: return shareable_type and shareable_id
            $androidAppLink = [
                'shareable_type' => $shareLink->shareable_type,
                'shareable_id' => $shareLink->shareable_id
            ];
            
            // For iOS app: return ref and key
            $iosAppLink = [
                'ref' => strtolower(substr($shareLink->shareable_type, 0, 1)), // First character of type (v for vendor, p for product)
                'key' => $shareLink->shareable_id
            ];
            
            return response()->json([
                'success' => true,
                'message' => __('api.success'),
                'data' => [
                    'androidapplink' => $androidAppLink,
                    'iosapplink' => $iosAppLink
                ]
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => __('api.not_found')
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the share link data'
            ], 500);
        }
    }
} 