<?php

namespace App\Http\Resources\V3_1;

use App\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'hash_id' => $this->hash_id,
            'title' => $this->title,
            'description' => $this->description,
            'taxonomy_id' => $this->taxonomy_id,
            'taxonomy_name' => $this->taxonomy_name,
            'taxonomy_type' => $this->taxonomy_type,
            'price' => (float) $this->price,
            'review' => $this->review,
            'terms' => $this->terms,
            'url' => $this->url,
            'likes_count' => (int) $this->likes_count,
            'is_liked' => (bool) $this->is_liked,
            'shared_link' => $this->shared_link,
            'payment_details' => (float) $this->payment_details,
            'offer' => OfferResource::make($this->whenLoaded('offer')),
            'vendor' => VendorResource::make($this->whenLoaded('vendor')),
            'images' => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
