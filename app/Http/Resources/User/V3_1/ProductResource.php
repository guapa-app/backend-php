<?php

namespace App\Http\Resources\User\V3_1;

use App\Models\Vendor;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        $returned_arr = [
            'id'                           => $this->id,
            'hash_id'                      => (string) $this->hash_id,
            'title'                        => (string) $this->title,
            'description'                  => (string) $this->description,
            'address'                      => (string) $this->address,
            'taxonomy_name'                => (string) $this->taxonomy_name,
            'price'                        => (float) $this->price,
            'type'                         => $this->type,
            'terms'                        => (string) $this->terms,
            'is_liked'                     => (bool) $this->is_liked,
            'likes_count'                  => (int) $this->likes_count,
            'shared_link'                  => $this->shared_link,

            'payment_details'              => $this->payment_details,
            'points'                       => $this->calcProductPoints(),
            'offer'                        => OfferResource::make($this->whenLoaded('offer')),
            'vendor'                       => $this->whenLoaded('vendor', function () {
                return [
                    'id'   => $this->vendor->id,
                    'name' => (string) $this->vendor->name,
                    'logo' => $this->vendor->logo ? MediaResource::make($this->vendor->logo) : null,
                    'type' => Vendor::TYPES[$this->vendor->type],
                ];
            }),
            'images'                       => MediaResource::collection($this->whenLoaded('media')),
        ];

        if ($this->type->value == 'product') {
            $returned_arr = array_merge($returned_arr, [
                'stock' => (int) $this->stock,
                'is_shippable' => (bool) $this->is_shippable,
                'min_quantity_per_user' => (int) $this->min_quantity_per_user,
                'max_quantity_per_user' => (int) $this->max_quantity_per_user,
                'is_out_of_stock' => (bool) $this->stock < $this->min_quantity_per_user,
            ]);
        }

        return $returned_arr;
    }
}
