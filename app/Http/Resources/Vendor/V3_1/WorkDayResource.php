<?php

namespace App\Http\Resources\Vendor\V3_1;

use App\Enums\WorkDay;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkDayResource extends JsonResource
{
    public function toArray($request)
    {
        $returned_arr = [
            'id'                   => $this->id,
            'day'                  => $this->day->name,
        ];

        return $returned_arr;
    }
}
