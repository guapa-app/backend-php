<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'name'                  => (string)$this->name,
            'email'                 => (string)$this->email,
            'phone'                 => (string)$this->phone,
            'status'                => (string)$this->status,
            'email_verified_at'     => (string)$this->email_verified_at,
            'phone_verified_at'     => (string)$this->phone_verified_at,
            'role'                  => $this->role,
            'user_vendors_ids'      => $this->user_vendors_ids,
            'profile'               => $this->whenLoaded('profile', ProfileResource::make($this->profile)),
        ];
    }
}
