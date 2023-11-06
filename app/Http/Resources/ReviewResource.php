<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'comment'       => $this->comment,
            'stars'         => $this->stars,

            'user'          => UserResource::make($this->whenLoaded('user')),

            'created_at'    => Carbon::parse($this->created_at)->diffForHumans(),
            'updated_at'    => Carbon::parse($this->updated_at)->diffForHumans(),
        ];
    }
}
