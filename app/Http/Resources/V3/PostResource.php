<?php

namespace App\Http\Resources\V3;

use App\Http\Resources\AdminResource;
use App\Http\Resources\TaxonomyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'content'       => $this->content,
            'youtube_url'   => $this->youtube_url,

            'is_liked'      => $this->is_liked,
            'created_at'    => $this->created_at,

            'admin'         => AdminResource::make($this->whenLoaded('admin'))->only(['id', 'name']),
            'category'      => TaxonomyResource::make($this->whenLoaded('category'))->only(['id', 'title']),
            'images'        => MediaResource::collection($this->whenLoaded('media')),
            'social_media'  => SocialMediaResource::collection($this->whenLoaded('socialMedia')),
        ];
    }
}
