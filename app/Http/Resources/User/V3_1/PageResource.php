<?php

namespace App\Http\Resources\User\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'title'                 => (string) $this->title,
            'content'               => (string) $this->content,
            'image'                 => MediaResource::make($this->whenLoaded('image')),
        ];
    }
}
