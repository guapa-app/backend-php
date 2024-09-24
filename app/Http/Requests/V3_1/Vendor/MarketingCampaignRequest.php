<?php

namespace App\Http\Requests\V3_1\Vendor;

use App\Enums\MarketingCampaignAudienceType;
use App\Enums\MarketingCampaignChannel;
use App\Enums\MarketingCampaignType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class MarketingCampaignRequest extends FailedValidationRequest
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
            'channel'       => ['required', new Enum(MarketingCampaignChannel::class)],
            'audience_type' => ['required', new Enum(MarketingCampaignAudienceType::class)],
            'audience_count'     => 'required|integer|min:1',
            'type'  => ['required', 'string', new Enum(MarketingCampaignType::class)],
            'id'    => 'required|integer',
            'users' => 'nullable|array',
            'users.*' => 'integer|exists:users,id',
        ];
    }
}
