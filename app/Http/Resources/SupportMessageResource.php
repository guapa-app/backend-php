<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupportMessageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'subject'               => (string)$this->subject,
            'body'                  => (string)$this->body,
            'phone'                 => (string)$this->phone,
            'is_read'               => (bool)$this->is_read,
            'user'                  => UserResource::make($this->whenLoaded('user')),
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
        ];
    }
}
