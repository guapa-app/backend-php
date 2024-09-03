<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Contracts\Repositories\OfferRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\V3_1\TaxonomyRepositoryInterface;
use App\Enums\ProductType;
use App\Http\Resources\V3_1\CategoryResource;
use App\Http\Resources\V3_1\ProductResource;
use Illuminate\Http\Request;

class HomeController
{
    public function index(
        Request $request,
        TaxonomyRepositoryInterface $taxRepository,
        OfferRepositoryInterface $offerRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $data['categories'] = CategoryResource::collection(
            $taxRepository->getData(with: ['icon'], where: ['type' => 'category'], isPaginated: true)
        );

        $data['offers'] = ProductResource::collection(
            $offerRepository->all($request)
        );

        $request->type = ProductType::Product->value;
        $data['products'] = ProductResource::collection(
            $productRepository->all($request)
        );

        $request->type = ProductType::Service->value;
        $data['services'] = ProductResource::collection(
            $productRepository->all($request)
        );

        return $data;
    }
}
