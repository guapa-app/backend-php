<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    public function toArray($request)
    {
        $returned_arr = [
            'id'                    => $this->id,
            'product_id'            => $this->product_id,
            'title'                 => $this->title,
            'description'           => $this->description,
            'terms'                 => $this->terms,
            'price'                 => number_format($this->price, 2, '.', ''), // Ensuring one decimal place
            'discount'              => $this->discount,
            'discount_string'       => $this->discount_string,
            'status'                => $this->status,
            'starts_at'             => $this->starts_at->format('Y-m-d H:i:s'),
            'expires_at'            => $this->expires_at->format('Y-m-d H:i:s'),
            'expires_countdown'     => $this->expires_countdown,
            'image'                 => MediaResource::make($this->whenLoaded('image')),
        ];

        return $returned_arr;
    }
}
