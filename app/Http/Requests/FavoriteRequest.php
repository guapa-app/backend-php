<?php

namespace App\Http\Requests;

use App\Models\User;

/**
 * @bodyParam type string required Object type (vendor, product or post). Example: product
 * @bodyParam id int required Object id. Example: 4
 */
class FavoriteRequest extends FailedValidationRequest
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
            'type' => 'required|string|in:' . implode(',', User::FAVORITE_TYPES),
            'id'   => 'required|integer',
        ];
    }
}
