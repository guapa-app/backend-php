<?php

namespace App\Http\Resources\V3_1;

use App\Http\Resources\AddressResource;
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
            'whatsapp' => (string) $this->whatsapp,
            'twitter' => (string) $this->twitter,
            'instagram' => (string) $this->instagram,
            'working_days' => (string) $this->working_days,
            'working_hours' => (string) $this->working_hours,
            'snapchat' => (string) $this->snapchat,
            'website_url' => (string) $this->website_url,
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
            'logo' => MediaResource::make($this->whenLoaded('logo')),
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'services' => ProductResource::collection($this->whenLoaded('services')),
        ];
    }
}
