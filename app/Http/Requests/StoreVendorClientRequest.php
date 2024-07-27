<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendorClientRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:vendor_clients',
            'phone' => 'required|string|unique:vendor_clients',
            'address' => 'required|string',
            'country' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|string',
            'gender' => 'required|string',
            'dob' => 'required|date',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
