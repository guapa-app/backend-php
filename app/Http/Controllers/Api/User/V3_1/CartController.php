<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3_1\User\Cart\AddToCartRequest;
use App\Http\Requests\V3_1\User\Cart\IncrementOrDecrementQuantityRequest;
use App\Http\Requests\V3_1\User\Cart\RemoveFromCartRequest;
use App\Services\V3_1\CartService;
use Illuminate\Http\JsonResponse;

class CartController extends BaseApiController
{
    public function __construct(
        private CartService $cartService
    ) {
        parent::__construct();
    }

    public function addToCart(AddToCartRequest $request): JsonResponse
    {
        $this->cartService->addToCart(user: auth()->user(), productId: $request->product_id, quantity: $request->quantity);

        return $this->successJsonRes(message: __('api.cart.product_added_to_cart'));
    }

    public function getCart(): JsonResponse
    {
        $cart = $this->cartService->getCart(user: auth()->user());
        return $this->successJsonRes(data: $cart, message: __('api.success'));
        
    }

    public function removeFromCart(RemoveFromCartRequest $request): JsonResponse
    {
        $this->cartService->removeFromCart(user: auth()->user(), productId: $request->product_id);
        return $this->successJsonRes(message: __('api.cart.product_removed_from_cart'));
    }

    public function clearCart(): JsonResponse
    {
        $this->cartService->clearCart(user: auth()->user());
        return $this->successJsonRes(message: __('api.cart.cart_cleared'));
    }

    public function incrementQuantity(IncrementOrDecrementQuantityRequest $request): JsonResponse
    {
        $this->cartService->incrementQuantity(user: auth()->user(), productId: $request->product_id, quantity: $request->quantity);
        return $this->successJsonRes(message: __('api.cart.quantity_incremented'));
    }

    public function decrementQuantity(IncrementOrDecrementQuantityRequest $request): JsonResponse
    {
        $this->cartService->decrementQuantity(user: auth()->user(), productId: $request->product_id, quantity: $request->quantity);
        return $this->successJsonRes(message: __('api.cart.quantity_decremented'));
    }
}
