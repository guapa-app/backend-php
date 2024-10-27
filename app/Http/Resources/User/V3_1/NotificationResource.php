<?php

namespace App\Http\Resources\User\V3_1;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'data'        => (array) $this->data,
            'summary'     => (string) $this->summary,
            'is_read'     => (bool) $this->read_at,
            'created_at'  => Carbon::parse($this->created_at)->diffForHumans(),
            'updated_at'  => Carbon::parse($this->updated_at)->diffForHumans(),
        ];
    }
}
