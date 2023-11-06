<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'firstname'             => (string) $this->firstname,
            'lastname'              => (string) $this->lastname,
            'gender'                => (string) $this->gender,
            'birth_date'            => Carbon::parse($this->birth_date)->format('Y-m-d'),
            'about'                 => (string) $this->about,
            'settings'              => $this->settings,
            'photo'                 => PhotoResource::make($this->whenLoaded('photo')),
        ];
    }
}
