<?php

namespace App\Http\Resources\Vendor\V3_2;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentFormResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
        ];
    }
}
