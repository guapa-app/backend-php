<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\OfferController as ApiOfferController;
use App\Http\Requests\OfferRequest;
use App\Http\Resources\OfferResource;
use Illuminate;

class OfferController extends ApiOfferController
{
    public function create(OfferRequest $request)
    {
        return OfferResource::make(parent::create($request))
            ->additional([
                "success" => true,
                'message' => __('api.success'),
            ]);
    }

    public function update(OfferRequest $request, $id)
    {
        return OfferResource::make(parent::update($request, $id))
            ->additional([
                "success" => true,
                'message' => __('api.success'),
            ]);
    }

    public function delete($id)
    {
        parent::delete($id);

        return $this->successJsonRes([], __('api.success'));
    }
}
