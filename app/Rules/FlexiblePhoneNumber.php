<?php

namespace App\Rules;

use Closure;
use App\Helpers\Common;
use App\Models\Setting;
use Illuminate\Contracts\Validation\ValidationRule;

class FlexiblePhoneNumber implements ValidationRule
{
    protected $isKsa = false;
    protected $isPreferred = false;

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) && !is_numeric($value)) {
            $fail(__('api.invalid_phone_format'));
            return;
        }

        $phone = (string) $value;

        // Check if system allows all mobile numbers
        if (Setting::isAllMobileNumsAccepted()) {
            // Basic phone number validation - must be reasonable length and format
            if (!preg_match('/^[\d\+\-\(\)\s]{7,20}$/', $phone)) {
                $fail(__('api.invalid_phone_format'));
                return;
            }

            // Check if it's a KSA number for priority
            if (Common::validateSaudiMobileNumber($phone)) {
                $this->isKsa = true;
                $this->isPreferred = true;
            } else {
                $this->isKsa = false;
                $this->isPreferred = false;
            }
            return;
        }

        // System requires KSA numbers only
        if (!Common::validateSaudiMobileNumber($phone)) {
            $fail(__('api.invalid_saudi_mobile_number'));
            return;
        }

        $this->isKsa = true;
        $this->isPreferred = true;
    }

    /**
     * Check if the validated number is a KSA number
     */
    public function isKsaNumber(): bool
    {
        return $this->isKsa;
    }

    /**
     * Check if the number is preferred (KSA number)
     */
    public function isPreferred(): bool
    {
        return $this->isPreferred;
    }

    /**
     * Get the normalized phone number (for KSA numbers)
     */
    public function getNormalizedNumber(string $phone): ?string
    {
        if ($this->isKsa) {
            return Common::normalizeSaudiMobileNumber($phone);
        }
        return $phone;
    }
}
