<?php

namespace App\Observers;

use App\Enums\ProductStatus;
use App\Events\ProductCreated;
use App\Helpers\Common;
use App\Models\Product;
use Exception;
use Illuminate\Support\Str;

class ProductObserver
{
    /**
     * Handle the Product "creating" event.
     *
     * @param Product $product
     * @return void
     */
    public function creating(Product $product)
    {
        $product->hash_id = Common::generateUniqueHashForModel(Product::class, 1000000, true);
    }

    /**
     * Handle the Product "created" event.
     *
     * @param Product $product
     * @return void
     */
    public function created(Product $product)
    {
        // Generate unique identifier
        $identifier = Str::uuid();
        $link = url("/s/{$identifier}?ref=p&key=$product->id");

        $product->shareLink()->createQuietly([
            // Generate unique identifier
            'identifier' => $identifier,
            'link' => $link,
        ]);

        event(new ProductCreated($product));
    }

    public function deleting(Product $product)
    {
        if ($product->orderItems->count()) {
            $product->status = ProductStatus::Draft;
            $product->save();
            throw new Exception("Cannot delete this product. We draft it instead.");
        }

        return true;
    }
}
