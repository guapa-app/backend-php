<?php

namespace App\Http\Resources\Vendor\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                  => $this->id,
            'title'               => $this->title,
            'description'         => $this->description,
            'terms'               => $this->terms,
            'discount_string'     => $this->discount_string,
            'discount'            => $this->discount,
            'starts_at'           => $this->starts_at->format('Y-m-d H:i:s'),
            'expires_at'          => $this->expires_at->format('Y-m-d H:i:s'),
            'price'               => number_format((float) $this->price, 1, '.', ''), // Ensuring one decimal place
            'status'              => $this->status,
            'expires_countdown'   => $this->expires_countdown,
            'image'               => MediaResource::make($this->whenLoaded('image')),
        ];
    }
}
