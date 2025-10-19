<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MarketingCampaignStatus: string implements HasLabel
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case EXPIRED = 'expired';
    case FAILED = 'failed';
    case Refunded = 'refunded';
    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::EXPIRED => 'Expired',
            self::FAILED => 'Failed',
            self::Refunded => 'Refunded',
        };
    }
}
