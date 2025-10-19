<?php

namespace App\Observers;

use App\Enums\ProductStatus;
use App\Events\ProductCreated;
use App\Helpers\Common;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Notifications\ProductIsUnshippableNotification;
use App\Notifications\ProductOutOfStockNotification;
use App\Services\ShareLinkService;
use Exception;
use Illuminate\Support\Facades\Notification;
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
//        $identifier = Str::uuid();
//        $link = url("/s/{$identifier}?ref=p&key=$product->id");
//
//        $product->shareLink()->createQuietly([
//            // Generate unique identifier
//            'identifier' => $identifier,
//            'link' => $link,
//        ]);
        // create shrer link from service
        $data = [
            'type' => 'product',
            'id' => $product->id,
        ];

        $shareLinkService = new ShareLinkService();
        $shareLinkService->create($data);

        event(new ProductCreated($product));
    }

    public function updated(Product $product)
    {
        if ($product->isDirty('stock') && $product->stock < $product->min_quantity_per_user) {
            $users = User::whereHas('carts', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })->get();

            foreach ($users as $user) {
                // fire notification to all users that has this order in the cart
                $user->notify(new ProductOutOfStockNotification(product: $product));
            }

            // notify the vendor
            Notification::send($product->vendor, new ProductOutOfStockNotification(product: $product, isToUser: false));
            
            // remove the product from the cart
            Cart::where('product_id', $product->id)->delete();
        }

        if($product->isDirty('is_shippable') && $product->is_shippable == 0) {
            $users = User::whereHas('carts', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })->get();

            foreach ($users as $user) {
                // fire notification to all users that has this order in the cart
                $user->notify(new ProductIsUnshippableNotification(product: $product));
            }

            // remove the product from the cart
            Cart::where('product_id', $product->id)->delete();
        }
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
