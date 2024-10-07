<?php

namespace App\Http\Resources\Vendor\V3_1;

use App\Http\Resources\TaxonomyResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $returned_arr = [
            'id'                                        => $this->id,
            'name'                                      => (string) $this->name,
            'email'                                     => (string) $this->email,
            'phone'                                     => (string) $this->phone,
            'about'                                     => (string) $this->about,
            'status'                                    => $this->resource::STATUSES[$this->status],
            'verified'                                  => (bool) $this->verified,
            'is_deleted'                                => (bool) $this->deleted_at,

            'logo'                                      => MediaResource::make($this->whenLoaded('logo')),
            'specialties'                               => TaxonomyResource::collection($this->whenLoaded('specialties')),
        ];

        return $returned_arr;
    }
}
