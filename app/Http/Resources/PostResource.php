<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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

            "likes_count"   => $this->likes_count,
            "is_liked"      => $this->is_liked,

            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,

            "admin"       => AdminResource::make($this->whenLoaded('admin')),
            "category"    => TaxonomyResource::make($this->whenLoaded('category')),
            "images"      => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
