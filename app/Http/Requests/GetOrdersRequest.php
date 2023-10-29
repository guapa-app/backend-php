<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class GetOrdersRequest extends FormRequest
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
        $rules = [];

        if ($this->user() && !$this->user()->isAdmin()) {
            $rules = [
                'vendor_id' => 'sometimes|integer',
                'status'    => 'sometimes|array',
                'status.*'  => [new Enum(OrderStatus::class)],
            ];
        }

        return $rules;
    }
}
