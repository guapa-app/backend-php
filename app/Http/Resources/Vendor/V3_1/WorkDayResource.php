<?php

namespace App\Http\Resources\Vendor\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkDayResource extends JsonResource
{
    public function toArray($request)
    {
        $returned_arr = [
            'day'                  => $this->day->value,
            'day_name'             => $this->day->name,
            'start_time'           => $this->start_time,
            'end_time'             => $this->end_time,
        ];

        return $returned_arr;
    }
}
