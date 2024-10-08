<?php

namespace App\Http\Requests\V3_1\User;

use App\Enums\AppointmentOfferEnum;
use App\Http\Requests\FailedValidationRequest;
use Illuminate\Validation\Rule;

class AppointmentOfferRequest extends FailedValidationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
        $rules = [
            'taxonomy_id' => ['required', Rule::exists('taxonomies', 'id')],
            'vendor_ids' => ['required', 'array'],
            'vendor_ids.*' => [
                'required',
                Rule::exists('vendors', 'id')
            ],
            'notes' => ['required', 'string', 'max:5000'],
            'media' => ['nullable', 'array', 'min:1'],
            'media.*' => ['nullable', 'string'],
            'appointments.*' => ['required', 'array'],
            'appointments.*.appointment_form_id' => [
                'required',
                Rule::exists('appointment_forms', 'id')
            ],
            'appointments.*.key' => ['required', 'string', 'max:255'],
            'appointments.*.answer' => ['required', 'string', 'max:255'],
        ];
        return $rules;
    }
}
