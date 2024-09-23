<?php

namespace App\Http\Resources\V3_1\User;

use App\Http\Resources\MediaResource;
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
