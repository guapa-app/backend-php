<?php

namespace App\Filament\AffiliateMarketeer\Widgets;

use App\Models\User;
use App\Services\LoyaltyPointsService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AffiliateStateWidgets extends BaseWidget
{
    public ?int $userId = null; 
    protected function getStats(): array
    {
        $user = $this->userId ? User::find($this->userId) : auth()->user();
        return [
            Stat::make('wallet balance', $user->wallet?->balance ?? 0)
                ->description('Current Balance')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'), 

            Stat::make('Points Wallet', app(LoyaltyPointsService::class)->getTotalPoints(userId: $user->id))
                ->description('Current Points')
                ->descriptionIcon('heroicon-m-bolt')
                ->color('success'), 
        ];
    }
}
