<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'from'                  => $this->from_time,
            'to'                    => $this->to_time,
        ];
    }
}
