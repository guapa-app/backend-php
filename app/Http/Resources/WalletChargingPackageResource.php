<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletChargingPackageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'name'                 => $this->name,
            'amount'               => (int) $this->amount,
            'points'               => (int) $this->points
        ];
    }
}
