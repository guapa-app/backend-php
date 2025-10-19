<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\ProductListRequest;
use App\Http\Resources\User\V3_1\ProductCollection;
use App\Http\Resources\User\V3_1\ProductResource;

class ProductController extends BaseApiController
{
    protected $productRepository;
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        parent::__construct();

        $this->productRepository = $productRepository;
    }
    public function index(ProductListRequest $request)
    {
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
}
