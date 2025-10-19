<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

/**
 * @bodyParma vendor_id integer required Vendor id to add the staff to.
 * @bodyParam name string required Full name 3 to 100 characters
 * @bodyParam email string Email address
 * @bodyParam phone string required Phone number
 * @bodyParam role string required One of manager, doctor
 **/
class StaffRequest extends FailedValidationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && !$this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('id');

        $rules = [
            'vendor_id' => 'nullable|integer|exists:vendors,id',
            'name'      => 'required|string|min:3|max:100',
            'email'     => ['nullable', 'email', Rule::unique('users')],
            'phone'     => 'required|string|min:4|max:30|unique:users,phone',
            'role'      => 'required|string|in:manager,doctor',
        ];

        if (is_numeric($id)) {
            // Updating vendor staff data
            $rules = array_merge($rules, [
                'email' => ['nullable', 'email', Rule::unique('users')->ignore($id)],
                'phone' => ['required', 'string', 'min:4', 'max:30',
                    Rule::unique('users')->ignore($id),
                ],
            ]);
        }

        return $rules;
    }
}
