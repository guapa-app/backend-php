<?php

namespace App\Http\Requests;

class ProductListRequest extends FailedValidationRequest
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
        return [
            'vendor_id'         => 'nullable|integer',
            'category_ids'      => 'nullable|array',
            'category_ids.*'    => 'numeric|exists:taxonomies,id',
            'type'              => 'nullable|in:product,service',
            'list_type'         => 'nullable|in:default,most_viewed,most_ordered,offers',
            'keyword'           => 'nullable|string',
            'min_price'         => 'nullable|numeric',
            'max_price'         => 'nullable|numeric',
            'city_id'           => 'nullable|numeric|exists:cities:id',
            'lat'               => 'nullable|numeric|between:-90,90',
            'lng'               => 'nullable|numeric|between:-180,180',
            'distance'          => 'nullable|numeric',
            'page'              => 'nullable|numeric|min:1',
            'perPage'           => 'nullable|numeric|min:1',
        ];
    }
}
