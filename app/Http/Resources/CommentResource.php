<?php

namespace App\Http\Resources;

use App\Models\Post;
use App\Models\Product;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id"            => $this->id,
            "content"       => $this->content,
            "user_type"     => $this->user_type,

            "user"          => UserResource::make($this->whenLoaded('user')),
            "post"          => UserResource::make($this->whenLoaded('post')),

            'created_at'    => Carbon::parse($this->created_at)->diffForHumans(),
            'updated_at'    => Carbon::parse($this->updated_at)->diffForHumans(),
        ];
    }
}
