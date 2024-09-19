<?php

namespace App\Http\Resources\Vendor\V3_1;

use App\Http\Resources\AddressResource;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\StaffResource;
use App\Http\Resources\V3\SocialMediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorProfileResource extends JsonResource
{
    public function toArray($request)
    {
        $returned_arr = [
            'id'                                        => $this->id,
            'staff_id'                                  => $this->staff_id,
            'name'                                      => (string) $this->name,
            'email'                                     => (string) $this->email,
            'phone'                                     => (string) $this->phone,
            'about'                                     => (string) $this->about,
            'type'                                      => $this->resource::TYPES[$this->type],

            'status'                                    => $this->resource::STATUSES[$this->status],
            'verified'                                  => (bool) $this->verified,
            'is_deleted'                                => (bool) $this->deleted_at,

            'tax_number'                                => (string) $this->tax_number,
            'cat_number'                                => (string) $this->cat_number,
            'reg_number'                                => (string) $this->reg_number,
            'health_declaration'                        => (string) $this->health_declaration,

            'shared_link'                               => (string) $this->shared_link,

            'work_days'                                 => WorkDayResource::collection($this->whenLoaded('workDays')),
            'addresses'                                 => AddressResource::collection($this->whenLoaded('addresses')),
            'logo'                                      => MediaResource::make($this->whenLoaded('logo')),
            'specialties'                               => TaxonomyResource::collection($this->whenLoaded('specialties')),
            'appointments'                              => AppointmentResource::collection($this->whenLoaded('appointments')),
            'social_media'                              => SocialMediaResource::collection($this->whenLoaded('socialMedia')),
        ];

        return $returned_arr;
    }
}
