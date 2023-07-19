<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
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
            "category_ids"                          => $this->category_ids,
            "address_ids"                           => $this->address_ids,
            "likes_count"                           => $this->likes_count,
            "is_liked"                              => $this->is_liked,
            "offer"                                 => OfferResource::make($this->whenLoaded('offer')),
            "vendor"                                => VendorResource::make($this->whenLoaded('vendor')),
            "image"                                 => MediaResource::make($this->whenLoaded('image')),
        ];
    }
}
