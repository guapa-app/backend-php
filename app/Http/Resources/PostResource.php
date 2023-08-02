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

            'created_at'  => Carbon::parse($this->created_at)->diffForHumans(),
            'updated_at'  => Carbon::parse($this->updated_at)->diffForHumans(),

            "admin"       => AdminResource::make($this->whenLoaded('admin')),
            "category"    => TaxonomyResource::make($this->whenLoaded('category')),
            "images"      => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
