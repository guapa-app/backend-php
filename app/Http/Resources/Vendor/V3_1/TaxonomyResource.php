<?php

namespace App\Http\Resources\Vendor\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class TaxonomyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => (int) $this->id,
            'title'                 => (string) $this->title,
            'slug'                  => (string) $this->slug,
            'type'                  => (string) $this->type,
            'parent_id'             => $this->parent_id,
            'icon'                  => MediaResource::make($this->whenLoaded('icon')),
        ];
    }
}
