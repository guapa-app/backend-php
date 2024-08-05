<?php

namespace App\Http\Resources\V3;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VendorClientCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($client) {
                return [
                    'id' => $client->user->id,
                    'name' => $client->user->name,
                    'email' => $client->user->email,
                    'phone' => $client->user->phone,
                    'orders_count' => $client->user->orders_count,
                    // list of orders for the client url
                    'orders_url' => route('vendors.client.orders', [$client->vendor_id, $client->user->id]),
                ];
            }),
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}
