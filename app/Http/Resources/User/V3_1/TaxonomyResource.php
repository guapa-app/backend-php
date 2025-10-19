<?php

namespace App\Http\Resources\User\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class TaxonomyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'icon' => MediaResource::make($this->whenLoaded('icon')),
            'photo' => MediaResource::make($this->whenLoaded('photo')),
        ];
    }
}
