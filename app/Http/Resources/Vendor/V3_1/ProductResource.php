<?php

namespace App\Http\Resources\Vendor\V3_1;

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
            'status'                                => $this->status,
            'type'                                  => $this->type,
            'terms'                                 => (string) $this->terms,
            'is_liked'                              => (bool) $this->is_liked,
            'shared_link'                           => $this->shared_link,
            'payment_details'                       => $this->payment_details,

            'offer'                                 => OfferResource::make($this->whenLoaded('offer')),
            'addresses'                             => AddressResource::collection($this->whenLoaded('addresses')),
            'images'                                => MediaResource::collection($this->whenLoaded('media')),
        ];

        return $returned_arr;
    }
}
