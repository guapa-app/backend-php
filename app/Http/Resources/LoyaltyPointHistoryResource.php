<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoyaltyPointHistoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'points'               => (int) $this->points,
            'action'               => (string) $this->action,
            'type'                 => (string) $this->type,
            'title'                => (string) $this->title,
            'points_change'        => (string) $this->points_change,
        ];
    }
}
