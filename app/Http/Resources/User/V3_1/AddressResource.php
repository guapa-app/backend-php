<?php

namespace App\Http\Resources\User\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'title'                => (string) $this->title,
            'city'                 => CityResource::make($this->whenLoaded('city')),
            'address_1'            => (string) $this->address_1,
            'address_2'            => (string) $this->address_2,
            'lat'                  => (float) $this->lat,
            'lng'                  => (float) $this->lng,
        ];
    }
}
