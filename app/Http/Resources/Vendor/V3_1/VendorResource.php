<?php

namespace App\Http\Resources\Vendor\V3_1;

use App\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    public function toArray($request)
    {
        $returned_arr = [
            'id'                                        => $this->id,
            'staff_id'                                  => $this->staff_id,
            'name'                                      => (string) $this->name,
            'verified'                                  => (bool) $this->verified,
            'is_child'                                  => (bool) $this->parent_id,
            'is_deleted'                                => (bool) $this->deleted_at,
            'status'                                    => $this->resource::STATUSES[$this->status],
            'type'                                      => $this->resource::TYPES[$this->type],
            'logo'                                      => MediaResource::make($this->whenLoaded('logo')),
            'addresses'                                 => AddressResource::collection($this->whenLoaded('addresses')),
            // TODO get first one
            'specialties'                               => TaxonomyResource::collection($this->whenLoaded('specialties')),

            $this->mergeWhen($this->parent_id, [
                'parent_id'                                 => $this->parent_id,
                'parent_name'                               => $this->parent?->name,
                'parent_photo'                              => MediaResource::make($this->parent?->logo),
            ]),

        ];

        return $returned_arr;
    }
}
