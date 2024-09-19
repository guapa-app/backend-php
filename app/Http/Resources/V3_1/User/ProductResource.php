<?php

namespace App\Http\Resources\V3_1\User;

use App\Http\Resources\AddressResource;
use App\Http\Resources\MediaResource;
use App\Http\Resources\OfferResource;
use App\Http\Resources\VendorResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        $returned_arr = [
            'id'                                    => $this->id,
            'hash_id'                               => (string) $this->hash_id,
            'title'                                 => (string) $this->title,
            'description'                           => (string) $this->description,
            'address'                               => (string) $this->address,
            'taxonomy_name'                         => (string) $this->taxonomy_name,
            'price'                                 => (float) $this->price,
            'type'                                  => $this->type,
            'terms'                                 => (string) $this->terms,
            'is_liked'                              => (bool) $this->is_liked,
            'payment_details'                       => $this->payment_details,

            'offer'                                 => OfferResource::make($this->whenLoaded('offer')),
            'vendor'                                =>  [
                                                            'id'  => $this->vendor->id,
                                                            'name' => (string) $this->vendor->name
                                                        ],
            'images'                                => MediaResource::collection($this->whenLoaded('media')),
        ];

        return $returned_arr;
    }
}
