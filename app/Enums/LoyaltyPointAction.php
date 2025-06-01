<?php

namespace App\Enums;

enum LoyaltyPointAction: string
{
    case PURCHASE = 'purchase';
    case RETURN_PURCHASE = 'return_purchase';
    case REFERRAL = 'referral';
    case CONVERSION = 'conversion';
    case WALLET_CHARGING = 'wallet_charging';
    case SPIN_WHEEL = 'spin_wheel';
    case FRIENDS_REGISTRATIONS = 'friends_registrations';
    case SYSTEM_ADDITION = 'system_addition';
    case SYSTEM_DEDUCTION = 'system_deduction';
    case COUPON_EXCHANGE = 'coupon_exchange';
    case GIFT_CARD_EXCHANGE = 'gift_card_exchange';
    case PRODUCT_DISCOUNT = 'product_discount';
    case SHIPPING_DISCOUNT = 'shipping_discount';
    case TIER_UPGRADE = 'tier_upgrade';
}
