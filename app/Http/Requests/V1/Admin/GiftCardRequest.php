<?php

namespace App\Http\Requests\V1\Admin;

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
            'gift_type' => 'required|in:wallet,order',
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|size:3',

            // For order type gift cards
            'product_id' => 'nullable|exists:products,id',
            'offer_id' => 'nullable|exists:offers,id',
            'vendor_id' => 'nullable|exists:vendors,id',

            // Background customization
            'background_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'background_image_id' => 'nullable|integer|exists:gift_card_backgrounds,id',
            'background_image' => 'nullable|file|image|max:5120',

            // Gift card details
            'message' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'expires_at' => 'nullable|date|after:today',

            // Recipient information
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'nullable|email|max:255',
            'recipient_number' => 'nullable|string|max:20',

            // User management
            'user_id' => 'nullable|exists:users,id',
            'create_new_user' => 'nullable|boolean',
            'new_user_name' => 'required_if:create_new_user,true|string|max:255',
            'new_user_phone' => 'required_if:create_new_user,true|string|max:20',
            'new_user_email' => 'nullable|email|max:255',

            // Status management (for updates)
            'status' => 'sometimes|in:active,used,expired,cancelled',
        ];
    }

    public function messages()
    {
        return [
            'gift_type.required' => 'Please select the gift card type (wallet or order).',
            'gift_type.in' => 'Gift card type must be either wallet or order.',
            'recipient_name.required' => 'Recipient name is required.',
            'background_color.regex' => 'Background color must be a valid hex color code.',
            'amount.min' => 'Amount must be at least 1.',
            'currency.size' => 'Currency must be exactly 3 characters.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();

            // For order type, ensure either product_id or offer_id is provided
            if ($data['gift_type'] === 'order') {
                if (empty($data['product_id']) && empty($data['offer_id'])) {
                    $validator->errors()->add('gift_type', 'For order type gift cards, you must specify either a product or an offer.');
                }

                if (!empty($data['product_id']) && !empty($data['offer_id'])) {
                    $validator->errors()->add('gift_type', 'You cannot specify both product and offer for order type gift cards.');
                }
            }

            // Ensure either background_color or background_image_id is provided
            if (empty($data['background_color']) && empty($data['background_image_id']) && empty($data['background_image'])) {
                $validator->errors()->add('background', 'Please select either a background color or background image.');
            }

            // If creating new user, ensure user_id is not provided
            if (!empty($data['create_new_user']) && !empty($data['user_id'])) {
                $validator->errors()->add('user_id', 'Cannot specify both existing user and create new user.');
            }
        });
    }
}