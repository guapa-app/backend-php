<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WheelOfFortuneResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'rarity_title'         => $this->rarity_title,
            'probability'          => (int) $this->probability,
            'points'               => (int) $this->points
        ];
    }
}
