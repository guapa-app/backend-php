<?php

namespace App\Http\Resources\User\V3_1;

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
            'order'         => OrderResource::make($this->whenLoaded('order')),
            'image_before'  => MediaResource::make($this->whenLoaded('imageBefore')),
            'image_after'   => MediaResource::make($this->whenLoaded('imageAfter')),

            'created_at'    => Carbon::parse($this->created_at)->diffForHumans(),
            'updated_at'    => Carbon::parse($this->updated_at)->diffForHumans(),
        ];
    }
}
