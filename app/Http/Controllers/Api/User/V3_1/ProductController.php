<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\ProductController as ApiProductController;
use App\Http\Requests\ProductListRequest;
use App\Http\Resources\User\V3_1\ProductResource;

class ProductController extends ApiProductController
{
    public function index(ProductListRequest $request)
    {
        $index = parent::index($request);

        return ProductResource::collection($index)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function single($id)
    {
        $single = parent::single($id);

        return ProductResource::make($single)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
