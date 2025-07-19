<?php

namespace App\Filament\Admin\Widgets;

use App\Models\GiftCard;
use Illuminate\Support\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class GiftCardStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfYear = $now->copy()->startOfYear();
        $lastMonth = $now->copy()->subMonth();

        // Total gift cards
        $totalGiftCards = GiftCard::count();
        $activeGiftCards = GiftCard::where('status', GiftCard::STATUS_ACTIVE)->count();
        $usedGiftCards = GiftCard::where('status', GiftCard::STATUS_USED)->count();
        $expiredGiftCards = GiftCard::where('status', GiftCard::STATUS_EXPIRED)->count();

        // Amount statistics
        $totalAmount = GiftCard::sum('amount');
        $redeemedAmount = GiftCard::where('status', GiftCard::STATUS_USED)->sum('amount');
        $activeAmount = GiftCard::where('status', GiftCard::STATUS_ACTIVE)->sum('amount');

        // Monthly statistics
        $thisMonthGiftCards = GiftCard::whereMonth('created_at', $now->month)->count();
        $lastMonthGiftCards = GiftCard::whereMonth('created_at', $lastMonth->month)->count();
        $monthlyGrowth = $lastMonthGiftCards > 0 ? (($thisMonthGiftCards - $lastMonthGiftCards) / $lastMonthGiftCards) * 100 : 0;

        // Type distribution
        $walletType = GiftCard::where('gift_type', GiftCard::GIFT_TYPE_WALLET)->count();
        $orderType = GiftCard::where('gift_type', GiftCard::GIFT_TYPE_ORDER)->count();

        // Redemption statistics
        $pendingRedemption = GiftCard::where('redemption_method', 'pending')->count();
        $walletRedemption = GiftCard::where('redemption_method', 'wallet')->count();
        $orderRedemption = GiftCard::where('redemption_method', 'order')->count();

        // Expiring soon (next 30 days)
        $expiringSoon = GiftCard::where('status', GiftCard::STATUS_ACTIVE)
            ->where('expires_at', '>=', $now)
            ->where('expires_at', '<=', $now->copy()->addDays(30))
            ->count();

        return [
            Stat::make('Total Gift Cards', $totalGiftCards)
                ->description('All time gift cards created')
                ->descriptionIcon('heroicon-m-gift')
                ->color('primary'),

            Stat::make('Active Gift Cards', $activeGiftCards)
                ->description('Available for redemption')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([$activeGiftCards]),

            Stat::make('Used Gift Cards', $usedGiftCards)
                ->description('Successfully redeemed')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning')
                ->chart([$usedGiftCards]),

            Stat::make('Expired Gift Cards', $expiredGiftCards)
                ->description('Past expiration date')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger')
                ->chart([$expiredGiftCards]),

            Stat::make('Total Amount', number_format($totalAmount, 2) . ' SAR')
                ->description('Combined value of all gift cards')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'),

            Stat::make('Redeemed Amount', number_format($redeemedAmount, 2) . ' SAR')
                ->description('Total amount redeemed')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Active Amount', number_format($activeAmount, 2) . ' SAR')
                ->description('Available for redemption')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('primary'),

            Stat::make('This Month', $thisMonthGiftCards)
                ->description($monthlyGrowth >= 0 ? '+' . number_format($monthlyGrowth, 1) . '% from last month' : number_format($monthlyGrowth, 1) . '% from last month')
                ->descriptionIcon($monthlyGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($monthlyGrowth >= 0 ? 'success' : 'danger'),

            Stat::make('Wallet Type', $walletType)
                ->description('Gift cards for wallet credit')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('blue'),

            Stat::make('Order Type', $orderType)
                ->description('Gift cards for specific orders')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('purple'),

            Stat::make('Pending Redemption', $pendingRedemption)
                ->description('Awaiting redemption')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Wallet Redemptions', $walletRedemption)
                ->description('Redeemed to wallet')
                ->descriptionIcon('heroicon-m-arrow-right')
                ->color('success'),

            Stat::make('Order Redemptions', $orderRedemption)
                ->description('Redeemed as orders')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),

            Stat::make('Expiring Soon', $expiringSoon)
                ->description('Expires in next 30 days')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
