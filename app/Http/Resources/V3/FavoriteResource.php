<?php

namespace App\Http\Resources\V3;

use App\Http\Resources\PostResource;
use App\Http\Resources\V3\ProductResource;
use App\Http\Resources\VendorResource;
use App\Models\Offer;
use App\Models\Post;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    public function toArray($request)
    {
        if ($this->resource instanceof Post) {
            return new PostResource($this->resource);
        }

        if ($this->resource instanceof Vendor) {
            return new VendorResource($this->resource);
        }

        if ($this->resource instanceof Product) {
            return new ProductResource($this->resource);
        }

        if ($this->resource instanceof Offer) {
            return new ProductResource($this->resource->product);
        }
        return [];
    }
}
