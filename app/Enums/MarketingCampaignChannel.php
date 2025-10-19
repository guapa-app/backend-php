<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MarketingCampaignChannel: string implements HasLabel
{
    case WHATSAPP = 'whatsapp';

    public function getLabel(): string
    {
        return match ($this) {
            self::WHATSAPP => 'WhatsApp',
        };
    }
}
