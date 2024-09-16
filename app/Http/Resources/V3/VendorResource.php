<?php

namespace App\Http\Resources\V3;

use App\Http\Resources\AddressResource;
use App\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    public function toArray($request)
    {
        $returned_arr = [
            'id'                                        => $this->id,
            'name'                                      => (string) $this->name,
            'type'                                      => $this->resource::TYPES[$this->type],
            'logo'                                      => MediaResource::make($this->whenLoaded('logo')),
            'addresses'                                 => AddressResource::collection($this->whenLoaded('addresses')),
        ];

        return $returned_arr;
    }
}
