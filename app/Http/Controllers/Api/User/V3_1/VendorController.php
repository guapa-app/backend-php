<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\VendorController as ApiVendorController;
use App\Http\Resources\User\V3_1\VendorResource;
use Illuminate\Http\Request;

class VendorController extends ApiVendorController
{
    public function index(Request $request)
    {
        $index = parent::index($request);

        return VendorResource::collection($index)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function single(Request $request, $id)
    {
        $item = parent::single($request, $id);

        return VendorResource::make($item)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
