<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\User\V3_1\DataResource;

class DataController extends BaseApiController
{
    public function data()
    {
        $data = parent::data();

        return DataResource::make((object) $data)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function address_types()
    {
        return $this->successJsonRes(parent::address_types(), __('api.success'));
    }

    public function vendor_types()
    {
        return $this->successJsonRes(parent::vendor_types(), __('api.success'));
    }

    public function giftCardOptions()
    {
        return response()->json([
            'colors' => config('gift_card.colors'),
            'background_images' => config('gift_card.background_images'),
            'success' => true,
            'message' => __('api.success'),
        ]);
    }
}
