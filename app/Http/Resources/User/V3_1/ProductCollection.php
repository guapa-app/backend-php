<?php

namespace App\Http\Resources\User\V3_1;

use App\Http\Resources\MediaResource;
use App\Http\Resources\OfferResource;
use Illuminate\Http\Resources\MissingValue;

class ProductCollection extends GeneralCollection
{
    public function toArray($request)
    {
        return $this->prepareAddiitonal($request) + [
            'data' => [
                'items' => $this->collection->transform(function ($product) {
                    $returned_arr = [
                        'id'                                    => $product->id,
                        'hash_id'                               => (string) $product->hash_id,
                        'title'                                 => (string) $product->title,
                        'address'                               => (string) $product->address,
                        'taxonomy_name'                         => (string) $product->taxonomy_name,
                        'price'                                 => (float) $product->price,
                        'type'                                  => $product->type,
                        'is_liked'                              => (bool) $product->is_liked,
                        'offer'                                 => (object) [],
                        'images'                                => MediaResource::collection($product->whenLoaded('media')),
                    ];

                    if (!($product->whenLoaded('offer') instanceof MissingValue) && ($product->whenLoaded('offer') != null)) {
                        $returned_arr = array_merge($returned_arr, [
                            'offer'                             => OfferResource::make($product->offer),
                        ]);
                    }
                    if (!($product->whenLoaded('oldCurrentUpcomingOffer') instanceof MissingValue) && ($product->whenLoaded('oldCurrentUpcomingOffer') != null)) {
                        $returned_arr = array_merge($returned_arr, [
                            'offer'                             => OfferResource::make($product->whenLoaded('oldCurrentUpcomingOffer')),
                        ]);
                    }

                    return $returned_arr;
                }),
            ] + $this->preparePayload($request),
        ];
    }
}
