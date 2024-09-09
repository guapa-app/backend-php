<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\TaxRepositoryInterface;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\TaxonomyResource;

class HomeController
{
    public function index(TaxRepositoryInterface $taxRepository)
    {
        $categories = $taxRepository->getForApiData(['type' => 'category']);
        $data['categories'] = TaxonomyResource::collection($categories);

        $offers = auth()->user()->userVendor->vendor->products;
        $data['offers'] = ProductCollection::make($offers);

        return $data;
    }
}
