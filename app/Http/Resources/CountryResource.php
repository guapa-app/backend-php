<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'name'                 => $this->name,
            'currency_code'        => (string) $this->currency_code,
            'phone_code'           => (string) $this->phone_code,
            'phone_length'           => (int) $this->phone_length,
            'active'               => (bool) $this->active,
            'icon'               =>  $this->icon ? env('APP_URL') . '/storage/' . (string) $this->icon : "",
        ];
    }
}
