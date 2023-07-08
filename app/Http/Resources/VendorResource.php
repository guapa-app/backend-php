<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'name'                  => (string)$this->name,
            'email'                 => (string)$this->email,
            'phone'                 => (string)$this->phone,
            'about'                 => (string)$this->about,
            'status'                => (int)$this->status,
            'verified'              => (bool)$this->verified,
            'whatsapp'              => (string)$this->whatsapp,
            'twitter'               => (string)$this->twitter,
            'instagram'             => (string)$this->instagram,
            'type'                  => $this->type,
            'working_days'          => (string)$this->working_days,
            'working_hours'         => (string)$this->working_hours,
            'snapchat'              => (string)$this->snapchat,
            'website_url'           => (string)$this->website_url,
            'known_url'             => (string)$this->known_url,
            'tax_number'            => (string)$this->tax_number,
            'cat_number'            => (string)$this->cat_number,
            'reg_number'            => (string)$this->reg_number,
        ];
    }
}
