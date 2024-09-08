<?php

namespace App\Http\Resources\V3_1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InfluencerResource extends JsonResource
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
            'subject' => (string) $this->subject,
            'body' => (string) $this->body,
            'is_read' => (bool) $this->is_read,
            'status' => $this->status,
            'vendor' => VendorResource::make($this->whenLoaded('vendor')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
