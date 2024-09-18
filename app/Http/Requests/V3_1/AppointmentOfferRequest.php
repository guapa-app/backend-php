<?php

namespace App\Http\Requests\V3_1;

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
                'vendor_id' => [
                    'required',
                    Rule::exists('vendors', 'id')->whereNull('parent_id')
                ],
                'taxonomy_id' => ['required', Rule::exists('taxonomies', 'id')],
                'sub_vendor_ids' => ['required', 'array'],
                'sub_vendor_ids.*' => [
                    'required',
                    Rule::exists('vendors', 'id')
                        ->where('parent_id', request()->get('vendor_id'))
                ],
                'notes' => ['required', 'string', 'max:5000'],
                'media' => ['nullable', 'array', 'min:1'],
                'media.*' => ['nullable', 'image', 'max:10240'],
                'appointments.*' => ['required', 'array'],
                'appointments.*.appointment_form_id' => [
                    'required',
                    Rule::exists('appointment_forms', 'id')
                ],
                'appointments.*.appointment_form_value_id' => [
                    'required',
                    Rule::exists('appointment_form_values', 'id')
                ],
                'appointments.*.key' => ['required', 'string', 'max:255'],
                'appointments.*.answer' => ['required', 'string', 'max:255'],
            ];
        } else {
            $rules = [
                'appointment_offer_id' => ['required', Rule::exists('appointment_offers', 'id')],
                'status' => ['required', Rule::in(AppointmentOfferEnum::getValues())],
                'reject_reason' => ['nullable', 'string', 'max:5000'],
                'staff_notes' => [
                    'required_if:status,'.AppointmentOfferEnum::Accept->value, 'string', 'max:5000'
                ],
                'offer_notes' => [
                    'required_if:status,'.AppointmentOfferEnum::Accept->value, 'string', 'max:5000'
                ],
                'terms' => [
                    'required_if:status,'.AppointmentOfferEnum::Accept->value, 'string', 'max:5000'
                ],
                'offer_price' => [
                    'required_if:status,'.AppointmentOfferEnum::Accept->value, 'numeric', 'min:1'
                ],
                'starts_at' => [
                    'required_if:status,'.AppointmentOfferEnum::Accept->value, 'date', 'date_format:Y-m-d H:i'
                ],
                'expires_at' => [
                    'required_if:status,'.AppointmentOfferEnum::Accept->value,
                    'date',
                    'date_format:Y-m-d H:i',
                    'after:starts_at'
                ],
            ];
        }

        return $rules;
    }
}
