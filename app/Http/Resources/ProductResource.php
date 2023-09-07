<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id"                                    => $this->id,
            "hash_id"                               => (string)$this->hash_id,
            "vendor_id"                             => $this->vendor_id,
            "title"                                 => (string)$this->title,
            "description"                           => (string)$this->description,
            "taxonomy_name"                         => (string)$this->taxonomy_name,
            "price"                                 => (double)$this->price,
            "status"                                => (string)$this->status,
            "review"                                => (string)$this->review,
            "type"                                  => (string)$this->type,
            "terms"                                 => (string)$this->terms,
            "url"                                   => (string)$this->url,
            "likes_count"                           => (int)$this->likes_count,
            "is_liked"                              => (boolean)$this->is_liked,

            "offer"                                 => OfferResource::make($this->whenLoaded('offer')),
            "vendor"                                => VendorResource::make($this->whenLoaded('vendor')),
            "taxonomies"                            => TaxonomyResource::collection($this->whenLoaded('taxonomies')),
            "addresses"                             => AddressResource::collection($this->whenLoaded('addresses')),
            "images"                                => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
