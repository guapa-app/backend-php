<?php

namespace App\Http\Requests\V3_1\User;

use App\Http\Requests\FailedValidationRequest;

class GiftCardRequest extends FailedValidationRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'required|in:product,service,offer',
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|size:3',
            'product_id' => 'required_if:type,product,service|nullable|exists:products,id',
            'product_type' => 'required_if:type,service|in:product,service|nullable',
            'offer_id' => 'required_if:type,offer|nullable|exists:offers,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'background_color' => 'nullable|string',
            'background_image' => 'nullable|integer|exists:media,id',
            'message' => 'nullable|string|max:500',
            'recipient_name' => 'nullable|string|max:255',
            'recipient_email' => 'nullable|email|max:255',
            'recipient_number' => 'nullable|string|max:20',
            'expires_at' => 'nullable|date|after:today',
            'user_id' => 'nullable|exists:users,id',
            'create_new_user' => 'nullable|boolean',
            'new_user_name' => 'required_if:create_new_user,true|string|max:255',
            'new_user_phone' => 'required_if:create_new_user,true|string|max:20',
            'new_user_email' => 'nullable|email|max:255',
        ];
    }
}
