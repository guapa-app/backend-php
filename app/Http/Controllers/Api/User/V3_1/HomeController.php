<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Contracts\Repositories\OfferRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\TaxRepositoryInterface;
use App\Enums\ProductType;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\User\V3_1\HomeResource;
use Illuminate\Http\Request;

class HomeController extends BaseApiController
{
    public function index(
        Request $request,
        TaxRepositoryInterface $taxRepository,
        OfferRepositoryInterface $offerRepository,
        ProductRepositoryInterface $productRepository
    ) {
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
