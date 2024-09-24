<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Http\Controllers\Api\SupportMessageController as ApiSupportMessageController;
use App\Http\Requests\V3\SupportMessageRequest;
use App\Http\Resources\Vendor\V3_1\SupportMessageCollection;
use App\Http\Resources\Vendor\V3_1\SupportMessageResource;
use App\Http\Resources\Vendor\V3_1\SupportMessageTypeCollection;
use App\Models\SupportMessageType;
use Illuminate\Http\Request;

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
     * @return SupportMessageCollection
     */
    public function index(Request $request)
    {
        $request->merge([
            'user_id' => $this->user->id,
        ]);

        $records = parent::indexCommon($request);

        return SupportMessageCollection::make($records)
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
     * @param  SupportMessageRequest  $request
     * @return SupportMessageResource
     */
    public function create(SupportMessageRequest $request)
    {
        $record = parent::createCommon($request);

        $record->load('supportMessageType');

        return SupportMessageResource::make($record)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function single($id)
    {
        $record = parent::singleCommon($id);

        return SupportMessageResource::make($record)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function types()
    {
        $records = SupportMessageType::all();

        return SupportMessageTypeCollection::make($records)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
