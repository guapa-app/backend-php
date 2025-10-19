<?php

namespace App\Http\Requests;

use App\Enums\ReviewFeature;
use App\Models\Review;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;

/**
 * @bodyParam reviewable_type string Object type ('Order' or 'Consultation'). Example: Order
 * @bodyParam reviewable_id int Object id. Example: 4
 * @bodyParam stars int Number of stars. Example: 5
 * @bodyParam comment string Review comment. Example: Very good service
 * @bodyParam ratings array required Array of ratings for different features.
 * @bodyParam image_before string Base64 encoded image before service. Example: data:image/jpeg;base64,...
 * @bodyParam image_after string Base64 encoded image after service. Example: data:image/jpeg;base64,...
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
            'ratings' => ['required', 'array'], // Feature ratings array
            'ratings.*.feature' => ['required', new Enum(ReviewFeature::class)], // Validate feature names
            'ratings.*.rating' => ['required', 'numeric', 'min:1', 'max:5'], // Validate rating values
            'comment' => 'nullable|string',
            'stars' => 'nullable|integer|min:1|max:5',
            'image_before' => ['nullable', 'string', 'regex:/^data:image\/(png|jpg|jpeg|gif|svg|webp);base64,/'],
            'image_after' => ['nullable', 'string', 'regex:/^data:image\/(png|jpg|jpeg|gif|svg|webp);base64,/'],
        ];

        // For backward compatibility, both old and new format are supported
        if ($this->has('order_id')) {
            $rules['order_id'] = 'required|integer|exists:orders,id';
        } else {
            $rules['reviewable_type'] = [
                'required',
                'string',
                Rule::in(['Order', 'Consultation', 'App\\Models\\Order', 'App\\Models\\Consultation']),
            ];
            $rules['reviewable_id'] = 'required|integer';
            
            // Dynamic validation for reviewable_id based on reviewable_type
            $rules['reviewable_id'] .= '|exists:' . $this->getTableFromReviewableType() . ',id';
        }

        if ($this->user() && $this->user()->isAdmin()) {
            $rules['user_id'] = 'required|integer|exists:users,id';
        }

        return $rules;
    }

    /**
     * Get table name based on reviewable_type
     *
     * @return string
     */
    protected function getTableFromReviewableType(): string
    {
        $type = $this->input('reviewable_type');
        
        if (!$type) {
            return 'orders';
        }
        
        $map = [
            'Order' => 'orders',
            'App\\Models\\Order' => 'orders',
            'Consultation' => 'consultations',
            'App\\Models\\Consultation' => 'consultations',
        ];
        
        return $map[$type] ?? 'orders';
    }

    /**
     * Get custom validation messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'reviewable_type.in' => 'The reviewable type must be either Order or Consultation.',
            'reviewable_id.exists' => 'The selected reviewable item does not exist.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Convert simple type names to fully qualified class names
        if ($this->has('reviewable_type') && !str_contains($this->input('reviewable_type'), '\\')) {
            $this->merge([
                'reviewable_type' => 'App\\Models\\' . $this->input('reviewable_type')
            ]);
        }
    }
}