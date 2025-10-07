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

        if($items->isEmpty()){
            return [
                'items' => [],
                'has_discount' => false,
                'fixed_price' => 0,
                'fixed_price_with_discount' => 0,
                'guapa_fees' => 0,
                'guapa_fees_with_taxes' => 0,
                'vendor_price_with_taxes' => 0,
                'total_amount_with_taxes' => 0,
                'total_quantity' => 0,
            ];
        }

        $hasDiscount = false; 
        $items->map(function($item) use (&$hasDiscount){
            if($item->product->payment_details['fixed_price'] != $item->product->payment_details['fixed_price_with_discount']){
                $hasDiscount = true;
            }
        });

        $fixedPrice = $items->sum(function($item){
            return $item->quantity * $item->product->payment_details['fixed_price'];
        });

        $fixedPriceAfterDiscount = $items->sum(function($item){
            return $item->quantity * $item->product->payment_details['fixed_price_with_discount'];
        });

        $guapaFees = $items->sum(function($item){
            return $item->quantity * $item->product->payment_details['guapa_fees'];
        });

        $guapaFeesWithTaxes = $items->sum(function($item){
            return $item->quantity * $item->product->payment_details['guapa_fees_with_taxes'];
        });

        $vendorPriceWithTaxes = $items->sum(function($item){
            return $item->quantity * $item->product->payment_details['vendor_price_with_taxes'];
        });

        $totalAmountWithTaxes = $items->sum(function($item){
            return $item->quantity * $item->product->payment_details['total_amount_with_taxes'];
        });

        $existingVendorId = Cart::where('user_id', $user->id)->first()->product->vendor_id;
        $items = $items->map(function($item) use ($existingVendorId){
            [$errors, $hasErrors] = $this->checkProductErrors(product: $item->product, quantity: $item->quantity, existingVendorId: $existingVendorId);
            return [
                'product' => ProductResource::make($item->product),
                'price' => round($item->product->payment_details['fixed_price_with_discount'] * $item->quantity, 2),
                'quantity' => $item->quantity,
                'errors' => $errors,
                'hasErrors' => $hasErrors,
            ];
        });
        return [
            'items' => $items,
            'has_discount' => $hasDiscount,
            'fixed_price' => round($fixedPrice, 2), // fixed_price
            'fixed_price_with_discount' => round($fixedPriceAfterDiscount, 2), // fixed_price_with_discount 
            'guapa_fees' => round($guapaFees, 2), // guapa_fees
            'guapa_fees_with_taxes' => round($guapaFeesWithTaxes, 2), // guapa_fees_with_taxes
            'vendor_price_with_taxes' => round($vendorPriceWithTaxes, 2), // vendor_price_with_taxes
            'total_amount_with_taxes' => round($totalAmountWithTaxes ,2), // fixed_price_with_discount + guapa_fees_with_taxes
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

    public function checkout(User $user, $orderData)
    {
        $items = Cart::where('user_id', $user->id)->with([
            'product' => function ($query) {
                $query->withSingleRelations();
            }
        ])->get();

        if($items->isEmpty()){
            abort(422, __('api.cart.cart_is_empty'));
        }

        $existingVendorId = $items->first()->product->vendor_id;
        $products = [];
        $items = $items->map(function($item) use ($existingVendorId, &$products){
            [$errors, $hasErrors] = $this->checkProductErrors(product: $item->product, quantity: $item->quantity, existingVendorId: $existingVendorId);
            
            if($hasErrors){
                $errorMessage =  "Product {$item->product->title} has errors - ";
                $errorMessage .= implode(', ', $errors);
                abort(422, $errorMessage);
            }

            $products[] = [
                'id' => $item->product->id,
                'quantity' => $item->quantity,
            ];
        });

        $orderData['products'] = $products;

        return app(OrderService::class)->create(data: $orderData, type: 'cart');
    }

    private function getCartTotalQuantity(User $user){
        return (int) Cart::where('user_id', $user->id)->sum('quantity');
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

    private function checkProductErrors($product, $quantity, $existingVendorId)
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
        if($existingVendorId != $product->vendor_id){
            $errors[] = __('api.cart.checkout_different_vendors_error');
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