<?php

namespace App\Http\Resources\V3;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'first_name'            => (string) $this->firstname,
            'last_name'             => (string) $this->lastname,
            'gender'                => (string) $this->gender,
            'birth_date'            => Carbon::parse($this->birth_date)->format('Y-m-d'),
            'about'                 => (string) $this->about,
            'settings'              => $this->settings,
            'photo'                 => MediaResource::make($this->whenLoaded('photo')),
        ];
    }
}
