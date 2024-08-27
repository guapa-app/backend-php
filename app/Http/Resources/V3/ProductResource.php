<?php

namespace App\Http\Resources\V3;

use App\Http\Resources\AddressResource;
use App\Http\Resources\MediaResource;
use App\Http\Resources\OfferResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        $returned_arr = [
            'id'                                    => $this->id,
            'hash_id'                               => (string) $this->hash_id,
//            'vendor_id'                             => $this->vendor_id,
            'title'                                 => (string) $this->title,
            'description'                           => (string) $this->description,
            'address'                               => (string) $this->address,
            'taxonomy_name'                         => (string) $this->taxonomy_name,
            'price'                                 => (float) $this->price,
            'status'                                => $this->status,
//            'review'                                => $this->review,
//            'type'                                  => $this->type,
            'terms'                                 => (string) $this->terms,
//            'url'                                   => (string) $this->url,
//            'likes_count'                           => (int) $this->likes_count,
//            'is_liked'                              => (bool) $this->is_liked,
            'payment_details'                        => $this->payment_details,
            'offer'                                 => OfferResource::make($this->whenLoaded('offer')),
//            'vendor'                                => VendorResource::make($this->whenLoaded('vendor')),
//            'taxonomies'                            => TaxonomyResource::collection($this->whenLoaded('taxonomies')),
            'addresses'                             => AddressResource::collection($this->whenLoaded('addresses')),
            'images'                                => MediaResource::collection($this->whenLoaded('media')),
        ];

        return $returned_arr;
    }
}
