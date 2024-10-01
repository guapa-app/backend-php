<?php

namespace App\Http\Requests\V3_1\Common;

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
        if (!$this->has('appointment_offer_id')) {
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
                'appointments.*.appointment_form_value_id' => [
                    'required',
                    Rule::exists('appointment_form_values', 'id')
                ],
                'appointments.*.key' => ['nullable', 'string', 'max:255'],
                'appointments.*.answer' => ['required', 'string', 'max:255'],
            ];
        } else {
            $accepted = AppointmentOfferEnum::Accept->value;
            $rules = [
                'appointment_offer_id' => ['required', Rule::exists('appointment_offers', 'id')],
                'status' => ['required', Rule::in(AppointmentOfferEnum::getValues())],
                'reject_reason' => ['nullable', 'string', 'max:5000'],
                'staff_notes' => ['required_if:status,'.$accepted, 'string', 'max:5000'],
                'offer_notes' => ['required_if:status,'.$accepted, 'string', 'max:5000'],
                'terms' => ['required_if:status,'.$accepted, 'string', 'max:5000'],
                'offer_price' => ['required_if:status,'.$accepted, 'numeric', 'min:1'],
                'starts_at' => ['required_if:status,'.$accepted, 'date_format:Y-m-d H:i'],
                'expires_at' => ['required_if:status,'.$accepted, 'date_format:Y-m-d H:i', 'after:starts_at'],
            ];
        }

        return $rules;
    }
}
