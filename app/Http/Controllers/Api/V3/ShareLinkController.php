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
            $shareLink = \App\Models\ShareLink::where('identifier', $identifier)->firstOrFail();

           return  $this->successJsonRes([
                'ref' => $shareLink->shareable_type,
                'key' => $shareLink->shareable_id
            ], __('api.success'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return $this->errorJsonRes([], __('api.not_found'));
        } catch (\Exception $e) {

            return $this->errorJsonRes([], __('api.error'), 500);
        }
    }
}
