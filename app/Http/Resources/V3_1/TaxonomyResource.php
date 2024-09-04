<?php

namespace App\Http\Resources\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class TaxonomyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'title' => $this->title,
            'icon' => MediaResource::make($this->whenLoaded('icon')),
        ];
    }
}
