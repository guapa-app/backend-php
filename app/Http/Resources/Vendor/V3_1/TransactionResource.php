<?php

namespace App\Http\Resources\Vendor\V3_1;

use Illuminate\Http\Request;
use App\Enums\TransactionType;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'transaction_number' => $this->transaction_number,
            'amount' => $this->amount,
            'transaction_type' => $this->transaction_type->value, // Use enum value
            'transaction_date' => $this->transaction_date->format('d M, Y'),
            'invoice_link' => $this->invoice_link,
            'status' => $this->status->value,
            'order_id' => $this->order_id,
        ];
    }
}
