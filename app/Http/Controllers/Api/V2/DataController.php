<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\DataResource;

class DataController extends BaseApiController
{
    public function data()
    {
        $data = parent::data();

        return DataResource::make((object)$data)
            ->additional([
                "success" => true,
                'message' => __('api.success'),
            ]);
    }
}
