<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVendorClientRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'string',
            'email' => 'email|unique:vendor_clients,email,' . $this->vendor_client->id,
            'phone' => 'string|unique:vendor_clients,phone,' . $this->vendor_client->id,
            'address' => 'string',
            'country' => 'string',
            'city' => 'string',
            'state' => 'string',
            'zip' => 'string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
