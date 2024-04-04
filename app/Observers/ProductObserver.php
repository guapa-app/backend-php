<?php

namespace App\Observers;

use App\Events\ProductCreated;
use App\Models\Product;

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
        $product->hash_id = rand(10000, 20000);
    }

    /**
     * Handle the Product "created" event.
     *
     * @param Product $product
     * @return void
     */
    public function created(Product $product)
    {
        event(new ProductCreated($product));
    }
}
