<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'status'        => (string)$this->status,
            'taxes'         => (float)$this->taxes,
            'amount'        => (float)$this->amount,
            'currency'      => (string)$this->currency,
            'amount_format' => (string)$this->amount_format,
            'url'           => (string)$this->url,

            'created_at'  => Carbon::parse($this->created_at)->diffForHumans(),
            'updated_at'  => Carbon::parse($this->updated_at)->diffForHumans(),
        ];
    }
}
