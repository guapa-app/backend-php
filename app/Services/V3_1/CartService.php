<?php 

namespace App\Services\V3_1;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;

class CartService
{
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
            ];
        });
        return [
            'items' => $items,
            'sub_total' => $subTotal,
            'taxes' => $subTotal * 0.15,
            'total' => $subTotal * 1.15,
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