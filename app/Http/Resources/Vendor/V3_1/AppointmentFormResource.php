<?php

namespace App\Http\Resources\Vendor\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentFormResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,

            $this->mergeWhen($this->pivot, [
                'key' => $this->pivot->key,
                'answer' => $this->pivot->answer,
            ]),
            'key' => $this->key
        ];
    }
}
