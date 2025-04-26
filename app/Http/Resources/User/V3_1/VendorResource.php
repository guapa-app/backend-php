<?php

namespace App\Http\Resources\User\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    public function toArray($request)
    {
        $returned_arr = [
            'id' => $this->id,
            'staff_id' => $this->staff_id,
            'name' => (string) $this->name,
            'parent_name' => $this->parent->name ?? null,
            'verified' => (bool) $this->verified,
            'verified_badge' => (bool) $this->verified_badge,
            'is_deleted' => (bool) $this->deleted_at,
            'is_liked'   => (bool) $this->is_liked,
            'likes_count' => (int) $this->likes_count,
            'status' => $this->resource::STATUSES[$this->status],
            'about' => (string) $this->about,
            'type' => $this->resource::TYPES[$this->type],
            'specialties' => TaxonomyResource::collection($this->whenLoaded('specialties')),
            'address' => $this->country?->name,
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
            'logo' => MediaResource::make($this->whenLoaded('logo')),
            'reviews_count' => $this->reviews_count,
            'rating' => $this->rating,
//            $this->mergeWhen(isset($this->distance), [
                'distance' => (float) $this->distance,
//            ]),

            $this->mergeWhen($this->accept_online_consultation, [
                'consultation_price' => $this->consultation_fees,
            ]),
        ];

        return $returned_arr;
    }
}
