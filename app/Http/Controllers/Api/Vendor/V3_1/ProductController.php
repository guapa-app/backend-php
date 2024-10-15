<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\ProductListRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\Vendor\V3_1\ProductResource;

class ProductController extends BaseApiController
{
    public function index(ProductListRequest $request)
    {
        $request->merge(['vendor_id' => $this->user->managerVendorId()]);

        $products = $this->productRepository->all($request);

        return ProductResource::collection($products)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function single($id)
    {
        $product = $this->productRepository->getOneWithRelations((int) $id);
        $product->description = strip_tags($product->description);

        return ProductResource::make($product)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function create(ProductRequest $request)
    {
        $data = $request->validated();
        $data['vendor_id'] = $this->user->managerVendorId();

        $item = $this->productService->create($data);

        return ProductResource::make($item)
            ->additional([
                'success' => true,
                'message' => __('api.created'),
            ]);
    }

    public function update($id, ProductRequest $request)
    {
        $item = $this->productService->update($id, $request->validated());

        return ProductResource::make($item)
            ->additional([
                'success' => true,
                'message' => __('api.updated'),
            ]);
    }

    public function delete($id)
    {
        $this->productService->delete($id);

        return $this->successJsonRes([], __('api.deleted'));
    }
}
