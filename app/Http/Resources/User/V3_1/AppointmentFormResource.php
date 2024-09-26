<?php

namespace App\Http\Resources\User\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentFormResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'type' => $this->type,
            $this->mergeWhen($this->pivot, [
                'key' => $this->pivot->key,
                'answer' => $this->pivot->answer,
            ]),
            'values' => AppointmentFormValuesResource::collection($this->whenLoaded('values')),
        ];
    }
}
