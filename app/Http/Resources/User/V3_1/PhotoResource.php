<?php

namespace App\Http\Resources\User\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class PhotoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'uuid'                 => $this->uuid,
            'name'                 => $this->name,
            'file_name'            => $this->file_name,
            'mime_type'            => $this->mime_type,
            'size'                 => $this->size,
            'order_column'         => $this->order_column,
            'url'                  => $this->url,
            'large'                => $this->large,
            'medium'               => $this->medium,
            'small'                => $this->small,
            'collection'           => $this->collection,
        ];
    }
}
