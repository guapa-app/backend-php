<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\ProductListRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\Vendor\V3_1\ProductCollection;
use App\Http\Resources\Vendor\V3_1\ProductResource;
use App\Services\ProductService;

class ProductController extends BaseApiController
{
    protected $productRepository;
    protected $productService;

    public function __construct(ProductRepositoryInterface $productRepository, ProductService $productService)
    {
        parent::__construct();

        $this->productRepository = $productRepository;
        $this->productService = $productService;
    }

    public function index(ProductListRequest $request)
    {
        $request->merge(['vendor_id' => $this->user->managerVendorId()]);

        $products = $this->productRepository->all($request);

        return ProductCollection::make($products)
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
        // temporary fix for the title, description, and terms translations
        if(isset($data['title'])){
            $data['title'] = [
                'en' => $data['title'],
                'ar' => $data['title'],
            ];
        }
        if(isset($data['description'])){
            $data['description'] = [
                'en' => $data['description'],
                'ar' => $data['description'],
            ];
        }
        if(isset($data['terms'])){
            $data['terms'] = [
                'en' => $data['terms'],
                'ar' => $data['terms'],
            ];
        }

        $data['vendor_id'] = $this->user->managerVendorId();
        $data['country_id'] = auth()->user()->country_id;
        
        $item = $this->productService->create($data);

        return ProductResource::make($item)
            ->additional([
                'success' => true,
                'message' => __('api.created'),
            ]);
    }

    public function update($id, ProductRequest $request)
    {
        $data = $request->validated();
        // temporary fix for the title, description, and terms translations
        if (isset($data['title'])) {
            $data['title'] = [
                'en' => $data['title'],
                'ar' => $data['title'],
            ];
        }
        if (isset($data['description'])) {
            $data['description'] = [
                'en' => $data['description'],
                'ar' => $data['description'],
            ];
        }
        if (isset($data['terms'])) {
            $data['terms'] = [
                'en' => $data['terms'],
                'ar' => $data['terms'],
            ];
        }

        $item = $this->productService->update($id, $data);

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
