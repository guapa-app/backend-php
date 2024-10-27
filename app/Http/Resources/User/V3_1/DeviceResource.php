<?php

namespace App\Http\Resources\User\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'user_type'     => (string) $this->user_type,
            'user_id'       => (string) $this->user_id,
            'guid'          => (string) $this->guid,
            'fcmtoken'      => (string) $this->fcmtoken,
            'type'          => (string) $this->type,
        ];
    }
}
