<?php

namespace App\Http\Requests;

use App\Enums\ReviewFeature;
use App\Models\Review;
use Illuminate\Validation\Rules\Enum;

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
            'order_id'   => 'required|integer|exists:orders,id',
            'stars'           => 'nullable|integer|min:1|max:5',
            'comment'         => 'nullable|string',
            'ratings' => ['required', 'array'], // Feature ratings array
            'ratings.*.feature' => ['required', new Enum(ReviewFeature::class)], // Validate feature names
            'ratings.*.rating' => ['required', 'numeric', 'min:1', 'max:5'], // Validate rating values

            'image_before' => ['nullable', 'string', 'regex:/^data:image\/(png|jpg|jpeg|gif|svg|webp);base64,/'],
            'image_after' => ['nullable', 'string', 'regex:/^data:image\/(png|jpg|jpeg|gif|svg|webp);base64,/'],
        ];

        if ($this->user() && $this->user()->isAdmin()) {
            $rules['user_id'] = 'required|integer|exists:users,id';
        }

        return $rules;
    }
}
