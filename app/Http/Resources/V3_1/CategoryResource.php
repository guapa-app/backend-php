<?php

namespace App\Http\Resources\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'title' => $this->title,
            'fixed_price' => $this->fixed_price,
            'description' => (string) $this->description,
            'icon' => MediaResource::make($this->whenLoaded('icon')),
        ];
    }
}
