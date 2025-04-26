<?php

namespace App\Http\Resources\User\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorDetailsResource extends JsonResource
{
    public function toArray($request)
    {
        $returned_arr = [
            'id' => $this->id,
            'staff_id' => $this->staff_id,
            'name' => (string) $this->name,
            'email' => (string) $this->email,
            'phone' => (string) $this->phone,
            'about' => (string) $this->about,
            'status' => $this->resource::STATUSES[$this->status],
            'verified' => (bool) $this->verified,
            'verified_badge' => (bool) $this->verified_badge,
            'is_deleted' => (bool) $this->deleted_at,
            'whatsapp' => (string) $this->whatsapp,
            'twitter' => (string) $this->twitter,
            'instagram' => (string) $this->instagram,
            'type' => $this->resource::TYPES[$this->type],
            'working_days' => (string) $this->working_days,
            'working_hours' => (string) $this->working_hours,
            'snapchat' => (string) $this->snapchat,
            'website_url' => (string) $this->website_url,
            'known_url' => (string) $this->known_url,

            'tax_number' => (string) $this->tax_number,
            'cat_number' => (string) $this->cat_number,
            'reg_number' => (string) $this->reg_number,

            'users_count' => (int) $this->users_count,
            'products_count' => (int) $this->products_count,
            'offers_count' => (int) $this->active_offers_count,
            'services_count' => (int) $this->services_count,

            'likes_count' => (int) $this->likes_count,
            'views_count' => (int) $this->views_count,
            'shares_count' => (int) $this->shares_count,
            'shared_link' => $this->shared_link,

            'is_liked' => (bool) $this->is_liked,
            'specialty_ids' => (array) $this->specialty_ids,
            'work_days' => $this->work_days,

            $this->mergeWhen(isset($this->orders_order_count), [
                'orders_order_count' => (int) $this->orders_order_count,
                'orders_consultations_count' => (int) $this->orders_consultations_count,
            ]),

            // $this->mergeWhen(isset($this->lat), [
            'lat' => (float) $this->lat ?? null,
            'lng' => (float) $this->lng ?? null,
            'address_1' => (string) $this->address_1 ?? null,
            'distance' => (float) $this->distance ?? null,
            // ]),

            'address' => $this->country?->name,
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
            'staff' => StaffResource::collection($this->whenLoaded('staff')),
            'logo' => MediaResource::make($this->whenLoaded('logo')),

            'specialties' => TaxonomyResource::collection($this->whenLoaded('specialties')),
            'appointments' => AppointmentResource::collection($this->whenLoaded('appointments')),
            'consultations' => ConsultationResource::collection($this->whenLoaded('consultations')),

            'social_media' => SocialMediaResource::collection($this->whenLoaded('socialMedia')),

            'products' => ProductResource::collection($this->whenLoaded('products')),
            'services' => ProductResource::collection($this->whenLoaded('services')),
            'offers' => ProductResource::collection($this->whenLoaded('productsHasOffers')),
            'reviews_count' => $this->reviews_count,
            'rating' => $this->rating,
        ];

        return $returned_arr;
    }
}
