<?php

namespace App\Http\Resources\Vendor\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorProfileResource extends JsonResource
{
    public function toArray($request)
    {
        $returned_arr = [
            'id'                                        => $this->id,
            'staff_id'                                  => $this->staff_id,
            'name'                                      => (string) $this->name,
            'email'                                     => (string) $this->email,
            'phone'                                     => (string) $this->phone,
            'about'                                     => (string) $this->about,
            'type'                                      => $this->resource::TYPES[$this->type],

            'working_days'                              => (string) $this->working_days,
            'working_hours'                             => (string) $this->working_hours,

            'status'                                    => $this->resource::STATUSES[$this->status],
            'verified'                                  => (bool) $this->verified,
            'is_deleted'                                => (bool) $this->deleted_at,

            'tax_number'                                => (string) $this->tax_number,
            'cat_number'                                => (string) $this->cat_number,
            'reg_number'                                => (string) $this->reg_number,
            'health_declaration'                        => (string) $this->health_declaration,

            'users_count'                               => (int) $this->users_count,
            'products_count'                            => (int) $this->products_count,
            'offers_count'                              => (int) $this->active_offers_count,
            'services_count'                            => (int) $this->services_count,

            'likes_count'                               => (int) $this->likes_count,
            'views_count'                               => (int) $this->views_count,
            'shares_count'                              => (int) $this->shares_count,
            'shared_link'                               => (string) $this->shared_link,

            'accept_appointment'                       => (bool) $this->accept_appointment,

            'activate_wallet'                            => (bool) $this->activate_wallet,
            'wallet_info_exists'                       => (bool) $this->iban,

            $this->mergeWhen(isset($this->orders_order_count), [
                'orders_order_count'                        => (int) $this->orders_order_count,
                'orders_consultations_count'                => (int) $this->orders_consultations_count,
            ]),

            $this->mergeWhen(isset($this->lat), [
                'lat'                                       => (float) $this->lat,
                'lng'                                       => (float) $this->lng,
                'address_1'                                 => (string) $this->address_1,
                'distance'                                  => (float) $this->distance,
            ]),

            'work_days'                                => WorkDayResource::collection($this->whenLoaded('workDays')),
            'addresses'                                => AddressResource::collection($this->whenLoaded('addresses')),
            'logo'                                     => MediaResource::make($this->whenLoaded('logo')),
            'specialties'                              => TaxonomyResource::collection($this->whenLoaded('specialties')),
            'appointments'                             => AppointmentResource::collection($this->whenLoaded('appointments')),
            'social_media'                             => SocialMediaResource::collection($this->whenLoaded('socialMedia')),

            'products'                                => ProductResource::collection($this->whenLoaded('products')),
            'services'                                => ProductResource::collection($this->whenLoaded('services')),
            'offers'                                  => ProductResource::collection($this->whenLoaded('productsHasOffers')),
        ];

        return $returned_arr;
    }
}
