<?php 

namespace App\Services\V3_1;

use App\Http\Resources\User\V3_1\MediaResource;
use App\Http\Resources\User\V3_1\OrderCollection;
use App\Models\Cart;
use App\Models\Product;
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
        $items = Cart::where('user_id', $user->id)->with('product')->get();
        $subTotal = $items->sum(function($item){
            return $item->quantity * $item->product->price;
        });

        $items = $items->map(function($item){
            return [
                'id' => $item->product->id,
                'title' => $item->product->title,
                'sub_price' => $item->product->price,
                'price' => $item->product->price * $item->quantity,
                'quantity' => $item->quantity,
                'images' => MediaResource::collection($item->product->media),
            ];
        });
        return [
            'items' => $items,
            'sub_total' => $subTotal,
            'taxes' => round($subTotal * $this->taxesPercentage / 100, 2),
            'total' => round($subTotal * (1 + $this->taxesPercentage / 100), 2),
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
}