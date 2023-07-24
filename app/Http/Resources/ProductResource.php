<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
//        return $this->resource;
        return [
            "id"                                    => $this->id,
            "vendor_id"                             => $this->vendor_id,
            "title"                                 => $this->title,
            "description"                           => $this->description,
            "price"                                 => $this->price,
            "status"                                => $this->status,
            "review"                                => $this->review,
            "type"                                  => $this->type,
            "terms"                                 => $this->terms,
            "url"                                   => $this->url,
            "likes_count"                           => $this->likes_count,
            "is_liked"                              => $this->is_liked,
            "offer"                                 => OfferResource::make($this->whenLoaded('offer')),
            "vendor"                                => VendorResource::make($this->whenLoaded('vendor')),
            "taxonomies"                            => TaxonomyResource::collection($this->whenLoaded('taxonomies')),
            "addresses"                             => AddressResource::collection($this->whenLoaded('addresses')),
            "images"                                => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
