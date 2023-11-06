<?php

namespace App\Http\Requests;

use App\Rules\ChatMessage;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam product_id int Product id, required if `conversation_id` is absent
 * @bodyParam conversation_id int Conversation id, required if `product_id` is absent
 * @bodyParam vendor_id int Vendor id, required for vendor app.
 * @bodyParam message string required The message can be a string, image, array of images.
 */
class MessagingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required_without:conversation_id|integer|exists:products,id',
            'message' => ['required', new ChatMessage()],
            'conversation_id' => 'required_without:product_id|integer|exists:conversations,id',
            'vendor_id' => 'sometimes|required|integer|exists:vendors,id', // For messages sent from vendor app
        ];
    }

    public function messages()
    {
        return [
            'product_id.required_without' => 'Please provide product id or conversation id',
            'conversation_id.required_without' => 'Please provide product id or conversation id',
        ];
    }
}
