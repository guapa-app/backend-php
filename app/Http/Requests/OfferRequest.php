<?php

namespace App\Http\Requests;

use App\Rules\ImageOrArray;

/**
 * @bodyParam product_id int required Product id for this offer.
 * @bodyParam discount integer required Discount percentage for this offer from 1 to 99.
 * @bodyParam title string Offer title 191 characters max.
 * @bodyParam description string Offer description 2000 characters max.
 * @bodyParam starts_at date The start date of the offer.
 * @bodyParam expires_at date The end date of the offer.
 */
class OfferRequest extends FailedValidationRequest
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
     * Get previous  the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // If the id exists this is an edit request
        $id = $this->route('id');

        $rules = [
            'product_id'    => 'required|integer|exists:products,id',
            'discount'      => 'required|integer|min:1|max:99',
            'title'         => 'nullable|string|max:200',
            'description'   => 'nullable|max:1000',
            'terms'         => 'nullable',
            'image'         => ['nullable', new ImageOrArray(), 'max:10240'],
        ];

        if (is_numeric($id)) {
            $rules['starts_at'] = 'nullable|date';
        } else {
            $rules['starts_at'] = 'nullable|date|after_or_equal:today';
        }

        if ($this->get('starts_at') != null) {
            $rules['expires_at'] = 'required|date|after_or_equal:starts_at';
        }

        return $rules;
    }
}
