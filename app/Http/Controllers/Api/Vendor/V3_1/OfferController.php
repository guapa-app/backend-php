<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Http\Controllers\Api\OfferController as ApiOfferController;
use App\Http\Requests\OfferRequest;
use App\Http\Resources\OfferResource;
use App\Http\Resources\ProductCollection;
use Illuminate\Http\Request;

class OfferController extends ApiOfferController
{
    /**
     * This API for vendors ONLY.
     * To display all products that has offer
     * even offers expired, active or incoming.
     *
     * @param Request $request
     * @return ProductCollection|object
     */
    public function index(Request $request)
    {
        $index = parent::index($request);

        return ProductCollection::make($index)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function create(OfferRequest $request)
    {
        return OfferResource::make(parent::create($request))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function update(OfferRequest $request, $id)
    {
        return OfferResource::make(parent::update($request, $id))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function delete($id)
    {
        parent::delete($id);

        return $this->successJsonRes([], __('api.success'));
    }
}
