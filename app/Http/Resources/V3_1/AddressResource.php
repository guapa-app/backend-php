<?php

namespace App\Http\Resources\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'city_id' => (int) $this->city_id,
            'city' => CityResource::make($this->whenLoaded('city')),
        ];
    }
}
