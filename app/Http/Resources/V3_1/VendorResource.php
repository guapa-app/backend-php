<?php

namespace App\Http\Resources\V3_1;

use App\Http\Resources\AddressResource;
use App\Http\Resources\V3\SocialMediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => (string) $this->name,
            'email' => (string) $this->email,
            'phone' => (string) $this->phone,
            'about' => (string) $this->about,
            'verified' => (bool) $this->verified,
            'working_days' => (string) $this->working_days,
            'working_hours' => (string) $this->working_hours,
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
            'logo' => MediaResource::make($this->whenLoaded('logo')),
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'services' => ProductResource::collection($this->whenLoaded('services')),
            'staff' => StaffResource::collection($this->whenLoaded('staff')),
            'specialties' => TaxonomyResource::collection($this->whenLoaded('specialties')),
            'social_media' => SocialMediaResource::collection($this->whenLoaded('socialMedia')),
        ];
    }
}
