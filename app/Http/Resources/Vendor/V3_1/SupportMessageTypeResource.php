<?php

namespace App\Http\Resources\Vendor\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class SupportMessageTypeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'name'                  => (string) $this->name,
        ];
    }
}
