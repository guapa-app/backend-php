<?php

namespace App\Http\Requests;

class CouponRequest extends FailedValidationRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|string|min:4|max:12|unique:coupons,code',
            'discount_percentage' => 'required|integer|min:1|max:100',
            'expires_at' => 'nullable|date',
            'max_uses' => 'nullable|integer|min:1',
            'vendor_id' => 'required|integer|exists:vendors,id',
            'products' => 'nullable|array',
            'single_user_usage' => 'required|integer|min:1',
        ];
    }
}
