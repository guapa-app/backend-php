<?php 

namespace App\Services\V3_1;

use App\Http\Resources\User\V3_1\ProductResource;
use App\Enums\ProductType;
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
            $this->incrementQuantity(user: $user, productId: $productId, quantity: $quantity ?? 1);
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
            [$errors, $hasErrors] = $this->checkProductErrors(product: $item->product, quantity: $item->quantity);
            return [
                'product' => ProductResource::make($item->product),
                'price' => $item->product->payment_details['price_after_discount'] * $item->quantity,
                'quantity' => $item->quantity,
                'errors' => $errors,
                'hasErrors' => $hasErrors,
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

        if(!$cart){
            abort(422, __('api.cart.product_not_found_in_cart'));
        }

        $this->checkProductQuantity(productId: $productId, cartQuantity: $cart->quantity, quantity: $quantity, isIncrement: true);
        if($cart){
            $cart->increment('quantity', $quantity ?? 1);
        }
    }

    public function decrementQuantity(User $user, int $productId, ?int $quantity = 1){
        $cart = Cart::where('user_id', $user->id)->where('product_id', $productId)->first();
        if($cart->quantity == $quantity){
            $this->removeFromCart(user: $user, productId: $productId);
            return;
        }

        if($quantity > $cart->quantity){
            abort(422, __('api.cart.quantity_is_greater_than_cart_quantity'));
        }

        $this->checkProductQuantity(productId: $productId, cartQuantity: $cart->quantity, quantity: $quantity, isIncrement: false);
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

    private function checkProductErrors($product, $quantity)
    {
        $errors = [];
        $hasErrors = false;

        if($product->type == ProductType::Service){
            $errors[] = __('api.cart.service_product_can_not_be_in_cart');
            $hasErrors = true;
        }
        if($quantity > $product->stock){
            $errors[] = __('api.cart.quantity_is_greater_than_available_in_stock');
            $hasErrors = true;
        }
        if($quantity < $product->min_quantity_per_user){
            $errors[] = __('api.cart.quantity_is_less_than_min_quantity_per_user');
            $hasErrors = true;
        }
        if($quantity > $product->max_quantity_per_user){
            $errors[] = __('api.cart.quantity_is_greater_than_max_quantity_per_user');
            $hasErrors = true;
        }
        if($product->is_shippable == false){
            $errors[] = __('api.cart.product_can_not_be_shipped');
            $hasErrors = true;
        }
        return [
            $errors,
            $hasErrors,
        ];
    }

    private function checkProductQuantity($productId, $cartQuantity, $quantity, $isIncrement = true){
        $product = Product::find($productId);
        if($isIncrement){
            if($product->stock < $cartQuantity + $quantity){
                abort(422, __('api.cart.quantity_is_greater_than_available_in_stock'));
            }
            if($product->min_quantity_per_user > $cartQuantity + $quantity){
                abort(422, __('api.cart.quantity_is_less_than_min_quantity_per_user'));
            }
            if($product->max_quantity_per_user < $cartQuantity + $quantity){
                abort(422, __('api.cart.quantity_is_greater_than_max_quantity_per_user'));
            }
        }else{
            if($product->stock < $cartQuantity - $quantity){
                abort(422, __('api.cart.quantity_is_greater_than_available_in_stock'));
            }
            if($product->min_quantity_per_user > $cartQuantity - $quantity){
                abort(422, __('api.cart.quantity_is_less_than_min_quantity_per_user'));
            }
            if($product->max_quantity_per_user < $cartQuantity - $quantity){
                abort(422, __('api.cart.quantity_is_greater_than_max_quantity_per_user'));
            }
        }
    }
}