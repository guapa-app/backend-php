<?php

namespace App\Http\Resources\Vendor\V3_1;

use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'balance' => (int) $this->wallet?->balance ?? 0,
            'transactions' => TransactionResource::collection($this->transactions),
        ];
    }
}
