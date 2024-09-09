<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MarketingCampaignAudienceType: string implements HasLabel
{
    case VENDOR_CUSTOMERS = 'vendor_customers';
    case GUAPA_CUSTOMERS = 'guapa_customers';


    public function getLabel(): string
    {
        return match ($this) {
            self::VENDOR_CUSTOMERS => 'Vendor Customers',
            self::GUAPA_CUSTOMERS => 'Guapa Customers',
        };
    }
}
