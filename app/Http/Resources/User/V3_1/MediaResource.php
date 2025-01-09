<?php

namespace App\Http\Resources\User\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class MediaResource extends JsonResource
{
    public function toArray($request)
    {
        $media =  [
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

        if (Str::startsWith($this->mime_type, 'video/')) {
            $media['thumbnail'] = $this->hasGeneratedConversion('thumb')
                ? $this->getUrl('thumb')
                : null;
        }
        return $media;
    }
}
