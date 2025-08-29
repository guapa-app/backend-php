<?php

namespace App\Http\Requests\V3_1\User\Cart;

use App\Enums\ProductType;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ];
    }

    public function withValidator($validator)
    {
        

        if (!$validator->passes() || $this->user()->isAdmin()) {
            return;
        }

        $validator->after(function ($validator) {
            $product = Product::find($this->product_id);
            if ($product->type == ProductType::Service) {
                $validator->errors()->add('product_id', __('api.cart.service_product_can_not_be_in_cart'));
            }
        });
    }
}
