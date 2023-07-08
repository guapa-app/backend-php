<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'firstname'             => (string)$this->firstname,
            'lastname'              => (string)$this->lastname,
            'gender'                => (string)$this->gender,
            'birth_date'            => $this->birth_date,
            'about'                 => (string)$this->about,
            'photo'                 => (string)$this->photo,
            'settings'              => (array)$this->settings,
        ];
    }
}
