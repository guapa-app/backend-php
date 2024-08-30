<?php

namespace App\Http\Requests;

use App\Models\MarketingCampaign;
use Illuminate\Foundation\Http\FormRequest;

class MarketingCampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vendor_id'          => 'required|exists:vendors,id',
            'channel'            => 'required|string|in:'. implode(',', MarketingCampaign::CHANNEL),
            'audience_type'      => 'required|string|in:guapa_customers,vendor_customers',
            'audience_count'     => 'required|integer|min:1',
            'type'  => 'required|string|in:'. implode(',', MarketingCampaign::TYPES),
            'id'    => 'required|integer',
            'users' => 'nullable|array',
            'users.*' => 'integer|exists:users,id',
        ];
    }
}
