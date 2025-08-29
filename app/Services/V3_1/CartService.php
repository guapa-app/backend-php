<?php 

namespace App\Services\V3_1;

use App\Http\Resources\User\V3_1\ProductResource;
use App\Models\Cart;
use App\Models\Setting;
use App\Models\User;

class CartService
{
    protected $productFees;
    protected $taxesPercentage;
    public function __construct(){
        $this->productFees = Setting::getProductFees();
        $this->taxesPercentage = Setting::getTaxes();
    }
    public function addToCart(User $user, int $productId, ?int $quantity = 1)
    {
        $cart = Cart::where('user_id', $user->id)->where('product_id', $productId)->first();
        if($cart){
            $cart->increment('quantity', $quantity ?? 1);
        }else{
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'quantity' => $quantity ?? 1,
            ]);
        }
    }

    public function removeFromCart(User $user, int $productId){
        $cart = Cart::where('user_id', $user->id)->where('product_id', $productId)->first();
        if($cart){
            $cart->delete();
        }
    }

    public function getCart(User $user){
        $items = Cart::where('user_id', $user->id)->with(['product' => function($query){
            $query->withSingleRelations();
        }])->get();

        $subTotal = $items->sum(function($item){
            return $item->quantity * $item->product->payment_details['price_after_discount'];
        });

        $fees = $items->sum(function($item){
            return $this->calculateProductFees(
                product: $item->product, 
                finalPrice: $item->product->payment_details['price_after_discount'] * $item->quantity
            );
        });

        $items = $items->map(function($item){
            return [
                'product' => ProductResource::make($item->product),
                'price' => $item->product->payment_details['price_after_discount'] * $item->quantity,
                'quantity' => $item->quantity,
            ];
        });
        return [
            'items' => $items,
            'sub_total' => $subTotal,
            'taxes' => round($fees, 2),
            'total' => round($subTotal + $fees, 2),
            'total_quantity' => $this->getCartTotalQuantity($user),
        ];
    }

    public function clearCart(User $user){
        Cart::where('user_id', $user->id)->delete();
    }

    public function incrementQuantity(User $user, int $productId, ?int $quantity = 1){
        $cart = Cart::where('user_id', $user->id)->where('product_id', $productId)->first();
        if($cart){
            $cart->increment('quantity', $quantity ?? 1);
        }
    }

    public function decrementQuantity(User $user, int $productId, ?int $quantity = 1){
        $cart = Cart::where('user_id', $user->id)->where('product_id', $productId)->first();
        if($cart){
            $cart->decrement('quantity', $quantity ?? 1);
        }
    }

    public function getCartTotalQuantity(User $user){
        return Cart::where('user_id', $user->id)->sum('quantity');
    }

    private function calculateProductFees($product, $finalPrice): float|int
    {
        $productCategory = $product->taxonomies()->first();

        if ($productCategory?->fees) {
            $productFees = $productCategory->fees;

            return ($productFees / 100) * $finalPrice;
        } else {
            return $productCategory?->fixed_price ?? 0;
        }
    }
}