<?php

namespace App\Http\Resources\Vendor\V3_1;

class PostCollection extends GeneralCollection
{
    public function toArray($request)
    {
        return $this->prepareAddiitonal($request) + [
                'data' => [
                        'items' => $this->collection->transform(function ($item) {
                            $returned_arr = [
                                'id'          => $item->id,
                                'title'       => $item->title,
                                'is_liked'    => $item->is_liked,
                                'created_at'  => $item->created_at,
                                'category'    => TaxonomyResource::make($item->whenLoaded('category'))->only(['id', 'title']),
                                'images'      => MediaResource::collection($item->whenLoaded('media')),
                            ];

                            return $returned_arr;
                        }),
                    ] + $this->preparePayload($request),
            ];
    }
}
