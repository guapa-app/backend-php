<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\SupportMessageController as ApiSupportMessageController;
use App\Http\Requests\SupportMessageRequest;
use App\Http\Resources\SupportMessageResource;

class SupportMessageController extends ApiSupportMessageController
{
    /**
     * Contact support.
     *
     * @responseFile 200 responses/general/contact.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     *
     * @unauthenticated
     *
     * @param SupportMessageRequest $request
     * @return SupportMessageResource
     */
    public function create(SupportMessageRequest $request)
    {
        $record = parent::createCommon($request);

        return SupportMessageResource::make($record)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
