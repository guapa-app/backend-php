<?php

namespace App\Http\Resources\V3_11;

use App\Http\Resources\AddressResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    public function toArray($request)
    {
        $returned_arr = [
            'id' => $this->id,
            'staff_id' => $this->staff_id,
            'name' => (string) $this->name,
            'verified' => (bool) $this->verified,
            'is_deleted' => (bool) $this->deleted_at,
            'status' => $this->resource::STATUSES[$this->status],
            'type' => $this->resource::TYPES[$this->type],
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
            'logo' => MediaResource::make($this->whenLoaded('logo')),
        ];

        return $returned_arr;
    }
}
