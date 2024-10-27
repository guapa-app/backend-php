<?php

namespace App\Http\Resources\User\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'content'     => $this->content,
            'status'      => $this->status,
            'youtube_url' => $this->youtube_url,

            'likes_count'   => $this->likes_count,
            'is_liked'      => $this->is_liked,

            'created_at'  => $this->created_at,

            'admin'       => AdminResource::make($this->whenLoaded('admin')),
            'category'    => TaxonomyResource::make($this->whenLoaded('category')),
            'images'      => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
