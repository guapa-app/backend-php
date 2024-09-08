<?php

namespace App\Http\Resources\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => (string) $this->name,
            'phone' => (string) $this->phone,
            'email' => (string) $this->email,
            'status' => (string) $this->status,

            $this->mergeWhen(isset($this->pivot), [
                'role' => (string) $this->pivot->role,
                'email' => (string) $this->pivot->email,
                'is_email_verified' => (bool) ($this->pivot->email_verified_at ?? $this->email_verified_at),
                'is_phone_verified' => (bool) ($this->pivot->phone_verified_at ?? $this->phone_verified_at),
            ]),
        ];
    }
}
