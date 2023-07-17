<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaxonomyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id"                    => (int)$this->id,
            "title"                 => (string)$this->title,
            "slug"                  => (string)$this->slug,
            "fees"                  => (double)$this->fees,
            "description"           => (string)$this->description,
            "font_icon"             => (string)$this->font_icon,
            "type"                  => (string)$this->type,
            "parent_id"             => $this->parent_id,

            $this->whenPivotLoaded('taxonomies', [
                "taxable_id"            => (int)$this->pivot->taxable_id,
                "taxonomy_id"           => (int)$this->pivot->taxonomy_id,
                "taxable_type"          => (string)$this->pivot->taxable_type,
            ]),
        ];
    }
}
