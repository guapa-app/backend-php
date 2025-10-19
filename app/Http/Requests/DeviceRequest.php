<?php

namespace App\Http\Requests;

use App\Models\Device;

/**
 * @bodyParam fcmtoken string required Fcm token
 * @bodyParam guid     string required Unique identifier for device
 * @bodyParam type     string required Device type `android`, `ios`, `desktop`
 */
class DeviceRequest extends FailedValidationRequest
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
            'fcmtoken' => 'required|string|max:191',
            'guid'     => 'required|string|max:191',
            'type'     => 'required|in:' . implode(',', Device::TYPES),
        ];
    }
}
