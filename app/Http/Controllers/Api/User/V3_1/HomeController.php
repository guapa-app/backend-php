<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Contracts\Repositories\OfferRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\TaxRepositoryInterface;
use App\Enums\ListTypeEnum;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\TaxonomyResource;
use Illuminate\Http\Request;

class HomeController
{
    public function index(
        Request $request,
        TaxRepositoryInterface $taxRepository,
        OfferRepositoryInterface $offerRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $categories = $taxRepository->getForApiData(['type' => 'category']);
        $data['categories'] = TaxonomyResource::collection($categories);

        $request->list_type = ListTypeEnum::Offers->value;
        $offers = $offerRepository->all($request);
        $data['offers'] = ProductCollection::make($offers);

        $request->list_type = ListTypeEnum::Default->value;
        $products = $productRepository->all($request);
        $data['products'] = ProductCollection::make($products);

        return $data;
    }
}
