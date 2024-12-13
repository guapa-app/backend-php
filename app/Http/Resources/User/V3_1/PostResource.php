<?php

namespace App\Http\Resources\User\V3_1;

use App\Enums\PostType;
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

            'type'        => $this->type,
            'stars'       => $this->stars,
            'show_user'   => $this->show_user,
            'service_date'=> $this->service_date,

            'likes_count'   => $this->likes_count,
            'is_liked'      => $this->is_liked,
            'comments_count'=> $this->comments_count,

            'created_at'  => $this->created_at,

            $this->mergeWhen($this->type == PostType::Vote->value, [
                'vote_options' => VoteOptionResource::collection($this->whenLoaded('voteOptions')),
            ]),

            $this->mergeWhen($this->type == PostType::Review->value, [
                'product'     => ProductResource::make($this->whenLoaded('product')),
            ]),

            'admin'       => AdminResource::make($this->whenLoaded('admin')),

            $this->mergeWhen($this->user_id , [
                'user'        => UserResource::make($this->whenLoaded('user')),
            ]),

            'category'    => TaxonomyResource::make($this->whenLoaded('category')),
            'images'      => MediaResource::collection($this->whenLoaded('media')),
            'social_media'  => SocialMediaResource::collection($this->whenLoaded('socialMedia')),
        ];
    }
}
