<?php

namespace App\Http\Resources\User\V3_1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialMediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'name'                  => (string) $this->name,

            $this->mergeWhen(isset($this->pivot), [
                'link'                      => (string) $this->pivot?->link,
            ]),

            'icon'                  => MediaResource::make($this->whenLoaded('icon')),
        ];
    }
}
