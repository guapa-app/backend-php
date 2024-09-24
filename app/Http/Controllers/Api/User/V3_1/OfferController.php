<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\OfferController as ApiOfferController;
use App\Http\Resources\User\V3_1\ProductResource;
use Illuminate\Http\Request;

class OfferController extends ApiOfferController
{
    /**
     * This API for vendors ONLY.
     * To display all products that has offer
     * even offers expired, active or incoming.
     *
     * @param  Request  $request
     * @return ProductResource|object
     */
    public function index(Request $request)
    {
        $index = parent::index($request);

        return ProductResource::collection($index)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
