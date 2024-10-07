<?php

namespace App\Http\Resources\User\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentOfferFormResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            $this->mergeWhen($this->pivot, [
                'key' => $this->pivot->key,
                'answer' => $this->pivot->answer,
                'answer_array'=> explode(',',$this->pivot->answer),
            ]),
        ];
    }
}
