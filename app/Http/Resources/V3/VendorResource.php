<?php

namespace App\Http\Resources\V3;

use App\Http\Resources\AddressResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    public function toArray($request)
    {
        $returned_arr = [
            'id'                                        => $this->id,
            'staff_id'                                  => $this->staff_id,
            'name'                                      => (string) $this->name,
            'type'                                      => $this->resource::TYPES[$this->type],
            'addresses'                                 => AddressResource::collection($this->whenLoaded('addresses')),
        ];

        return $returned_arr;
    }
}
