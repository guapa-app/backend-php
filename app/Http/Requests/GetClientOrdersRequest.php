<?php

namespace App\Http\Requests;

use App\Enums\ProductType;
use Illuminate\Validation\Rule;

class GetClientOrdersRequest extends FailedValidationRequest
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
            'product_type' => [
                'nullable',
                Rule::in([ProductType::Product, ProductType::Service]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'product_type.in' => 'The product type must be either "product" or "service".',
        ];
    }
}
