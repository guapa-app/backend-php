<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoyaltyPointHistoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'title'            => (string) $this->title,
            'points_change'    => (string) $this->points_change,
            'points'           => (int) $this->points,
            'action'           => (string) $this->action,
            'type'             => (string) $this->type,
            'date'             => $this->created_at->format('Y M d'),
            // 'date'           => Carbon::parse($this->created_at)->translatedFormat(' Y F j')
        ];
    }
}
