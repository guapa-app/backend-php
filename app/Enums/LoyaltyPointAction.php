<?php

namespace App\Enums;

enum LoyaltyPointAction: string
{
    case PURCHASE = 'purchase';
    case REFERRAL = 'referral';
    case CONVERSION = 'conversion';
    case WALLET_CHARGING = 'wallet_charging';
    case SPIN_WHEEL = 'spin_wheel';
}
