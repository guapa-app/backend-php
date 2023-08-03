<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\OfferController as ApiOfferController;
use App\Http\Requests\OfferRequest;
use Illuminate;

class OfferController extends ApiOfferController
{
    public function create(OfferRequest $request)
    {
        return response()->json(parent::create($request));
    }

    public function update(OfferRequest $request, $id)
    {
        return response()->json(parent::update($request, $id));
    }

    public function delete($id)
    {
        parent::delete($id);

        return $this->successJsonRes([
            'id' => $id,
        ], __('api.success'));
    }
}
