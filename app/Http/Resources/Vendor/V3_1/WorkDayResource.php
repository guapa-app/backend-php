<?php

namespace App\Http\Resources\Vendor\V3_1;

use App\Enums\WorkDay;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkDayResource extends JsonResource
{
    public function toArray($request)
    {
//        dd($this);
        $returned_arr = [
            'id'                   => $this->value,
            'day'                  => $this->name,
        ];

        return $returned_arr;
    }
}
