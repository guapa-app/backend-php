<?php

namespace App\Http\Resources\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'discount' => $this->discount,
            'starts_at' => $this->starts_at,
            'expires_at' => $this->expires_at,
        ];
    }
}
