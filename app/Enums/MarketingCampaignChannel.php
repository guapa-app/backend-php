<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MarketingCampaignChannel :string implements HasLabel
{
    case WHATSAPP = 'whatsapp';
//    case EMAIL = 'email';
//    case SMS = 'sms';

    public function getLabel(): string
    {
        return match ($this) {
            self::WHATSAPP => 'WhatsApp',
//            self::EMAIL => 'Email',
//            self::SMS => 'SMS',
        };
    }
}
