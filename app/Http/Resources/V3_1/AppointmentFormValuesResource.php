<?php

namespace App\Http\Resources\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentFormValuesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
        ];
    }
}
