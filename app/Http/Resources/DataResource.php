<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DataResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'specialties'                       => TaxonomyResource::collection($this->specialties),
            'categories'                        => TaxonomyResource::collection($this->categories),
            'blog_categories'                   => TaxonomyResource::collection($this->blog_categories),
            'address_types'                     => $this->address_types,
            'vendor_types'                      => $this->vendor_types,
            'cities'                            => CityResource::collection($this->cities),
            'settings'                          => $this->settings,
            'max_price'                         => $this->max_price,
            'product_fees'                      => $this->product_fees,
            'taxes_percentage'                  => $this->taxes_percentage,
        ];
    }
}
