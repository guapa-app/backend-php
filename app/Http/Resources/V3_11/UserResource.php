<?php

namespace App\Http\Resources\V3_11;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => (string) $this->name,
            'email' => (string) $this->email,
            'phone' => (string) $this->phone,
            'status' => (string) $this->status,
            'role' => $this->role,
            'user_vendors_ids' => $this->user_vendors_ids,

            $this->mergeWhen(!$this->relationLoaded('profile'), [
                'photo' => $this->photo,
            ]),

            'profile' => ProfileResource::make($this->whenLoaded('profile')),

            $this->mergeWhen($this->access_token, [
                'token' => $this->access_token,
            ]),
        ];
    }
}
