<?php

namespace App\Http\Requests;

use App\Models\Address;
use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
        $types = implode(',', array_keys(Address::TYPES));
        
        return [
            'title' => 'nullable|string|max:150',
            'addressable_type' => 'required|string|in:vendor,user',
            'addressable_id' => 'required|integer',
            'city_id' => 'sometimes|required|integer|exists:cities,id',
            'address_1' => 'required|string|max:250',
            'address_2' => 'nullable|string|max:250',
            'postal_code' => 'nullable|string',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'type' => 'required|integer|in:' . $types,
            'phone' => 'nullable|string|min:4|max:30',
        ];
    }
}
