<?php
namespace App\Filament\Admin\Resources\Info\ShareLinkResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ShareLinkAnalyticsStats extends BaseWidget
{
    public function getCards(): array
    {
        $record = $this->getRecord();
        $clicks = $record->clicks();

        return [
            Card::make('Total Clicks', $clicks->count())
                ->description('All time clicks')
                ->descriptionIcon('heroicon-o-cursor-click')
                ->chart($this->getClickTrend($clicks)),

            Card::make('Unique Visitors', $clicks->distinct('ip_address')->count())
                ->description('Based on IP addresses')
                ->descriptionIcon('heroicon-o-users'),

            Card::make('Platform Distribution', implode(' / ', [
                'Web: ' . $clicks->where('platform', 'web')->count(),
                'iOS: ' . $clicks->where('platform', 'ios')->count(),
                'Android: ' . $clicks->where('platform', 'android')->count(),
            ]))
                ->description('Platform breakdown')
                ->descriptionIcon('heroicon-o-device-mobile'),
        ];
    }

    private function getClickTrend($clicks)
    {
        return $clicks->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->limit(7)
            ->pluck('count')
            ->toArray();
    }

}
