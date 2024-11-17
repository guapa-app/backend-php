<?php

namespace App\Http\Resources\User\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    public function toArray($request)
    {
        $returned_arr = [
            'id' => $this->id,
            'staff_id' => $this->staff_id,
            'name' => (string) $this->name,
            'parent_name' => $this->parent->name ?? null,
            'verified' => (bool) $this->verified,
            'verified_badge' => (bool) $this->verified_badge,
            'is_deleted' => (bool) $this->deleted_at,
            'status' => $this->resource::STATUSES[$this->status],
            'type' => $this->resource::TYPES[$this->type],
            'specialties' => TaxonomyResource::collection($this->whenLoaded('specialties')),
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
            'logo' => MediaResource::make($this->whenLoaded('logo')),
        ];

        return $returned_arr;
    }
}
