<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\DataResource;

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
}
