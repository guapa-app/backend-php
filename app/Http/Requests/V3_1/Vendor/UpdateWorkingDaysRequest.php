<?php

namespace App\Http\Requests\V3_1\Vendor;

use App\Enums\WorkDay;
use App\Http\Requests\FailedValidationRequest;

class UpdateWorkingDaysRequest extends FailedValidationRequest
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
        $rules =  [
            'type' => 'sometimes|required|in:offline,online',
            'working_days' => 'sometimes|required|array|min:1',
        ];

        $hasAllDays = isset($this->working_days['all_days']);

        foreach (WorkDay::cases() as $day) {
            $dayName = strtolower($day->getLabel());
            $dayKey = str_replace(' ', '_', $dayName); // Convert "All Days" to "all_days"

            if ($dayKey !== 'all_days') {
                $rules["working_days.$dayKey"] = $hasAllDays ? 'nullable|array' : 'required_without:working_days.all_days|array';
                $rules["working_days.$dayKey.is_active"] = 'sometimes|boolean';
                $rules["working_days.$dayKey.from"] = "required_if:working_days.$dayKey.is_active,true|date_format:H:i";
                $rules["working_days.$dayKey.to"] = "required_if:working_days.$dayKey.is_active,true|date_format:H:i|after:working_days.$dayKey.from";
            }

            if ($hasAllDays) {
                $rules["working_days.all_days"] = 'nullable|array';
                $rules["working_days.all_days.is_active"] = 'sometimes|boolean';
                $rules["working_days.all_days.from"] = "required_if:working_days.all_days.is_active,true|date_format:H:i";
                $rules["working_days.all_days.to"] = "required_if:working_days.all_days.is_active,true|date_format:H:i|after:working_days.all_days.from";
            }
        }

        return $rules;
    }
}
