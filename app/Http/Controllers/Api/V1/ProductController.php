<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ProductController as ApiProductController;
use App\Http\Requests\ProductListRequest;
use App\Http\Requests\ProductRequest;

class ProductController extends ApiProductController
{
    public function index(ProductListRequest $request)
    {
        return response()->json(parent::index($request));
    }

    public function single($id)
    {
        return response()->json(parent::single($id));
    }

    public function create(ProductRequest $request)
    {
        return response()->json(parent::create($request));
    }

    public function update($id, ProductRequest $request)
    {
        return response()->json(parent::update($id, $request));
    }

    public function delete(int $id)
    {
        return response()->json([
            'message' => __('api.product_deleted'),
            'id' => parent::delete($id),
        ]);
    }
}
