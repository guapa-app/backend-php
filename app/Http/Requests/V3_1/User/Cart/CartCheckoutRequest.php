<?php

namespace App\Http\Requests\V3_1\User\Cart;

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
            'address_id' => 'required|integer|exists:addresses,id',
            'coupon_code' => 'sometimes|string|exists:coupons,code',
        ];
    }
}
