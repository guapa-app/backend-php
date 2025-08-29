<?php

namespace App\Http\Requests\V3_1\User\Cart;

use App\Enums\ProductType;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class CartCheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'note' => 'nullable|string|max:1000',
            'name' => 'required|string|max:60',
            'phone' => 'required|string|max:30',
            'products' => 'required|array|min:1',
            'address_id' => 'required|integer|exists:addresses,id',
            'coupon_code' => 'sometimes|string|exists:coupons,code',

            'products.*' => 'required|array',
            'products.*.id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1|max:10000',
        ];
    }

    public function withValidator($validator)
    {
        if (!$validator->passes() || $this->user()->isAdmin()) {
            return;
        }

        $validator->after(function ($validator) {
            $inputProducts = $this->get('products');
            $productIds = array_map(function ($product) {
                return $product['id'];
            }, $inputProducts);

            // Products require address_id and Services require appointment_date
            $productQuery = Product::query()->whereIn('id', $productIds);

            $types = $productQuery->pluck('type')->toArray();
            $is_service = in_array(ProductType::Service, $types);

            // can't request service and product at same time
            if ($is_service) {
                $validator->errors()->add('order', __('api.cart.service_product_can_not_be_in_cart'));
            }

            if ($productQuery->where('type', 'product')->count() > 0 && $this->get('address_id') == null) {
                $validator->errors()->add('address_id', __('api.address_required'));
            }
        });
    }
}
