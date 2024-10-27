<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MarketingCampaignType: string implements HasLabel
{
    case OFFER = 'offer';
    case PRODUCT = 'product';

    public function getLabel(): string
    {
        return match ($this) {
            self::OFFER => 'offer',
            self::PRODUCT => 'product',
        };
    }
}
