<?php

namespace App\Http\Requests\V3_1\User;

use App\Models\GiftCardSetting;
use App\Rules\FlexiblePhoneNumber;
use App\Http\Requests\FailedValidationRequest;

class GiftCardRequest extends FailedValidationRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $minAmount = GiftCardSetting::getMinAmount();
        $maxAmount = GiftCardSetting::getMaxAmount();
        $maxFileSize = GiftCardSetting::getMaxFileSize();
        $allowedFileTypes = GiftCardSetting::getAllowedFileTypes();

        return [
            'gift_type' => 'required|in:wallet,order',
            'amount' => "required|numeric|min:{$minAmount}|max:{$maxAmount}",
            'currency' => 'required|string|size:3',

            // For order type gift cards - make both optional but at least one required
            'product_id' => 'nullable|exists:products,id',
            'offer_id' => 'nullable|exists:offers,id',
            'vendor_id' => 'required_if:gift_type,order|nullable|exists:vendors,id',

            // Background customization
            'background_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'background_image_id' => 'nullable|integer|exists:gift_card_backgrounds,id',
            'background_image' => 'nullable|integer|exists:media,id',

            // Gift card details
            'message' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'expires_at' => 'nullable|date|after:today',

            // Recipient information
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'nullable|email|max:255',
            'recipient_number' => ['nullable', 'string', 'max:20', new FlexiblePhoneNumber],

            // User management - at least one user identification method is required
            'user_id' => 'nullable|exists:users,id',
            'create_new_user' => 'nullable|boolean',
            'new_user_name' => 'required_if:create_new_user,true|string|max:255',
            'new_user_phone' => ['required_if:create_new_user,true', 'string', 'max:20', new FlexiblePhoneNumber],
            'new_user_email' => 'nullable|email|max:255',
        ];
    }

    public function messages()
    {
        $minAmount = GiftCardSetting::getMinAmount();
        $maxAmount = GiftCardSetting::getMaxAmount();

        return [
            'gift_type.required' => 'Please select the gift card type (wallet or order).',
            'gift_type.in' => 'Gift card type must be either wallet or order.',
            'product_id.required_if' => 'Product is required for order type gift cards.',
            'offer_id.required_if' => 'Offer is required for order type gift cards.',
            'vendor_id.required_if' => 'Vendor is required for order type gift cards.',
            'recipient_name.required' => 'Recipient name is required.',
            'background_color.regex' => 'Background color must be a valid hex color code.',
            'amount.min' => "Amount must be at least {$minAmount}.",
            'amount.max' => "Amount cannot exceed {$maxAmount}.",
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
                $validator->errors()->add('user_management', 'You cannot create a new user and select an existing user at the same time.');
            }

            // Ensure at least one user identification method is provided
            $hasUserIdentification = !empty($data['user_id']) ||
                                   !empty($data['create_new_user']) ||
                                   !empty($data['recipient_email']) ||
                                   !empty($data['recipient_number']);

            if (!$hasUserIdentification) {
                $validator->errors()->add('user_management', 'Please provide either a user ID, recipient email, recipient phone number, or create a new user.');
            }
        });
    }
}
