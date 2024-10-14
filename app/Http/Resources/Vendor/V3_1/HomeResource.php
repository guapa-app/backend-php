<?php

namespace App\Http\Resources\Vendor\V3_1;

use App\Http\Resources\User\V3_1\ProductResource;
use App\Http\Resources\User\V3_1\TaxonomyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'categories'                        => TaxonomyResource::collection($this->categories),
            'offers'                            => ProductResource::collection($this->offers),
            'products'                          => ProductResource::collection($this->products),
            'services'                          => ProductResource::collection($this->services),
        ];
    }
}
