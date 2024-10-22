<?php

namespace App\Http\Resources\Vendor\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'name'                  => (string) $this->name,
            'email'                 => (string) $this->email,
            'phone'                 => (string) $this->phone,
            'status'                => (string) $this->status,
            'user_vendors_ids'      => is_array($this->user_vendors_ids) ? $this->user_vendors_ids[0] : null,

            $this->mergeWhen(!$this->relationLoaded('profile'), [
                'photo'                        => $this->photo,
            ]),

            'vendor'                => VendorResource::make($this->whenLoaded('vendor')),
            'profile'               => ProfileResource::make($this->whenLoaded('profile')),

            $this->mergeWhen($this->access_token, [
                'token'                        => $this->access_token,
            ]),
        ];
    }
}
