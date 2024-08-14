<?php

namespace App\Http\Controllers\Api\V3;

use App\Http\Controllers\Api\SupportMessageController as ApiSupportMessageController;
use App\Http\Requests\V3\SupportMessageRequest;
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
     * @param $request
     * @return SupportMessageResource
     */
    public function index($request)
    {
        $record = parent::indexCommon($request);

        return SupportMessageResource::make($record)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

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

    public function single($id)
    {
        $item = parent::singleCommon($id);

        return SupportMessageResource::make($item)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

}
