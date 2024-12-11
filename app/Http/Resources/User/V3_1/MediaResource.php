<?php

namespace App\Http\Resources\User\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'file_name'             => $this->file_name,
            'mime_type'             => $this->mime_type,
            'size'                  => $this->size,
            'collection_name'       => $this->collection_name,
            'url' => $this->url,
            'large' => $this->large,
            'medium' => $this->medium,
            'small' => $this->small,
        ];
    }
}
