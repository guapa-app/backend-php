<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Contracts\Repositories\V3_1\OfferRepositoryInterface;
use App\Contracts\Repositories\V3_1\ProductRepositoryInterface;
use App\Contracts\Repositories\V3_1\TaxonomyRepositoryInterface;
use App\Http\Resources\V3_1\CategoryResource;
use App\Http\Resources\V3_1\ProductResource;

class HomeController
{
    public function index(
        TaxonomyRepositoryInterface $taxRepository,
        OfferRepositoryInterface $offerRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $data['categories'] = CategoryResource::collection(
            $taxRepository->getData(where: ['type' => 'category'], limit: 3)
        );

        $data['offers'] = ProductResource::collection(
            $offerRepository->getData(limit: 3)
        );

        $data['products'] = ProductResource::collection(
            $productRepository->getData(with: 'vendor', limit: 3)
        );

        return $data;
    }
}
