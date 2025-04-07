<?php

namespace App\Http\Resources\Vendor\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkDayResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            strtolower($this->day->getLabel()) => [
                'is_active' => $this->is_active,
                'from' => $this->start_time,
                'type'   => $this->type,
                'to' => $this->end_time,
            ],
        ];
    }
}
