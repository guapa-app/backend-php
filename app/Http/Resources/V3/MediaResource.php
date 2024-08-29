<?php

namespace App\Http\Resources\V3;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'file_name'             => $this->file_name,
            'url'                   => $this->url,
            'large'                 => $this->large,
            'medium'                => $this->medium,
            'small'                 => $this->small,
        ];
    }
}
