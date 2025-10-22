<?php

namespace App\Http\Requests\V3_1\Vendor;

use App\Enums\AppointmentOfferEnum;
use App\Http\Requests\FailedValidationRequest;
use Illuminate\Validation\Rule;

class AcceptAppointmentRequest extends FailedValidationRequest
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
        $accepted = AppointmentOfferEnum::Accept->value;
        $rules = [
            'appointment_offer_id' => ['required', Rule::exists('appointment_offers', 'id')],
            'status' => ['required', Rule::in(AppointmentOfferEnum::getValues())],
            'reject_reason' => ['nullable', 'string', 'max:5000'],
            'staff_notes' => ['required_if:status,'.$accepted, 'string', 'max:5000'],
            'offer_notes' => ['required_if:status,'.$accepted, 'string', 'max:5000'],
            'terms.en' => ['required_if:status,'.$accepted, 'string', 'max:5000'],
            'terms.ar' => ['required_if:status,'.$accepted, 'string', 'max:5000'],
            'offer_price' => ['required_if:status,'.$accepted, 'numeric', 'min:1'],
            'starts_at' => ['required_if:status,'.$accepted, 'date_format:Y-m-d H:i'],
            'expires_at' => ['required_if:status,'.$accepted, 'date_format:Y-m-d H:i', 'after:starts_at'],
        ];

        return $rules;
    }
}
