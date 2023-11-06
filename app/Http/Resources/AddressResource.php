<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray($request)
    {
        $resource = 'App\Http\Resources\\' . ucfirst($this->addressable_type) . 'Resource';

        return [
            'id'                   => $this->id,
            'addressable_type'     => (string) $this->addressable_type,
            'addressable_id'       => (int) $this->addressable_id,
            'title'                => (string) $this->title,
            'city_id'              => (int) $this->city_id,
            'address_1'            => (string) $this->address_1,
            'address_2'            => (string) $this->address_2,
            'postal_code'          => (string) $this->postal_code,
            'lat'                  => (float) $this->lat,
            'lng'                  => (float) $this->lng,
            'type'                 => $this->type,
            'phone'                => (string) $this->phone,
            'addressable'          => $resource::make($this->whenLoaded('addressable')),
            'city'                 => CityResource::make($this->whenLoaded('city')),
        ];
    }
}
