<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaxonomyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => (int) $this->id,
            'title'                 => (string) $this->title,
            'slug'                  => (string) $this->slug,
            'fees'                  => (float) $this->fees,
            'fixed_price'           => $this->fixed_price,
            'description'           => (string) $this->description,
            'font_icon'             => (string) $this->font_icon,
            'type'                  => (string) $this->type,
            'parent_id'             => $this->parent_id,
            'icon'                  => MediaResource::make($this->whenLoaded('icon')),
        ];
    }
}
