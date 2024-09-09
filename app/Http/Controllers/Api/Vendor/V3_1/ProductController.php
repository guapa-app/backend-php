<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Http\Controllers\Api\ProductController as ApiProductController;
use App\Http\Requests\ProductListRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\V3_1\ProductResource;

class ProductController extends ApiProductController
{
    public function index(ProductListRequest $request)
    {
        $request['vendor_id'] = $this->user->userVendor?->vendor_id;
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

    public function create(ProductRequest $request)
    {
        $item = parent::create($request);

        return ProductResource::make($item)
            ->additional([
                'success' => true,
                'message' => __('api.created'),
            ]);
    }

    public function update($id, ProductRequest $request)
    {
        $item = parent::update($id, $request);

        return ProductResource::make($item)
            ->additional([
                'success' => true,
                'message' => __('api.updated'),
            ]);
    }

    public function delete($id)
    {
        parent::delete($id);

        return $this->successJsonRes([], __('api.deleted'));
    }
}
