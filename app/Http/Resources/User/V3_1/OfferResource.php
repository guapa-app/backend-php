<?php

namespace App\Http\Resources\User\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'discount_string' => $this->discount_string,
            'discount' => $this->discount,
            'starts_at' => $this->starts_at,
            'expires_at' => $this->expires_at,
            'image' => MediaResource::make($this->whenLoaded('image')),
        ];
    }
}
