<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\OfferRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\V3_1\TaxonomyRepositoryInterface;
use App\Enums\ListTypeEnum;
use App\Enums\ProductType;
use App\Http\Resources\V3_1\TaxonomyResource;
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
        $data['categories'] = TaxonomyResource::collection(
            $taxRepository->getData(with: ['icon'], where: ['type' => 'category'], isPaginated: true)
        );

        $request['vendor_id'] = auth()->user()?->managerVendorId();
        $request['list_type'] = ListTypeEnum::Offers->value;
        $data['offers'] = ProductResource::collection(
            $offerRepository->all($request)
        );

        $request['type'] = ProductType::Product->value;
        $request['list_type'] = ListTypeEnum::Default->value;
        $data['products'] = ProductResource::collection(
            $productRepository->all($request)
        );

        $request['type'] = ProductType::Service->value;
        $data['services'] = ProductResource::collection(
            $productRepository->all($request)
        );

        return $data;
    }
}
