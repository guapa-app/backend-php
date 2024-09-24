<?php

namespace App\Http\Requests;

use App\Models\Review;

/**
 * @queryParam reviewable_type string required Object type (vendor or product). Example: product
 * @queryParam reviewable_id int required Object id. Example: 4
 * @queryParam page int Page number for pagination. Example: 1
 * @queryParam per_page Records per page (5 to 30). Example: 10
 */
class GetReviewsRequest extends FailedValidationRequest
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
            'reviewable_type'   => 'required|string|in:' . implode(',', Review::TYPES),
            'reviewable_id'     => 'required|integer',
            'page'              => 'sometimes|integer|min:1',
            'per_page'          => 'sometimes|integer|min:5|max:30',
        ];
    }
}
