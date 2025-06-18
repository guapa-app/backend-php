<?php

namespace App\Http\Resources\Vendor\V3_2;

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
            'address_1'            => (string) $this->address_1,
            'lat'                  => (float) $this->lat ?: null,
            'lng'                  => (float) $this->lng ?: null,
        ];
    }
}
