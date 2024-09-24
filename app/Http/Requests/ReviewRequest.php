<?php

namespace App\Http\Requests;

use App\Models\Review;

/**
 * @bodyParam reviewable_type string required Object type (vendor or product). Example: product
 * @bodyParam reviewable_id int required Object id. Example: 4
 * @bodyParam stars int required Number of stars. Example: 5
 * @bodyParam comment string Review comment. Example: Very good product
 */
class ReviewRequest extends FailedValidationRequest
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
        $rules = [
            'reviewable_type' => 'required|string|in:' . implode(',', Review::TYPES),
            'reviewable_id'   => 'required|integer',
            'stars'           => 'required|integer|min:1|max:5',
            'comment'         => 'nullable|string',
        ];

        if ($this->user() && $this->user()->isAdmin()) {
            $rules['user_id'] = 'required|integer|exists:users,id';
        }

        return $rules;
    }
}
