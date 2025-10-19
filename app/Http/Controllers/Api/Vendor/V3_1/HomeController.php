<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\OfferRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\V3_1\TaxonomyRepositoryInterface;
use App\Enums\ProductType;
use App\Http\Resources\Vendor\V3_1\HomeResource;
use Illuminate\Http\Request;

class HomeController
{
    public function index(
        Request $request,
        TaxonomyRepositoryInterface $taxRepository,
        OfferRepositoryInterface $offerRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $request['vendor_id'] = auth()->user()?->managerVendorId();

        $data['categories'] =  $taxRepository->getForApiData(['type' => 'category']);

        $data['offers'] = $offerRepository->all($request);

        $request['type'] = ProductType::Product->value;
        $data['products'] = $productRepository->all($request);

        $request['type'] = ProductType::Service->value;
        $data['services'] = $productRepository->all($request);

        return HomeResource::make((object) $data)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
