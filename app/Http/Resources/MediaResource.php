<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'file_name'             => $this->file_name,
            'mime_type'             => $this->mime_type,
            'url'                   => $this->url,
        ];
    }
}
