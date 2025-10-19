<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Vendor;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VendorStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Calculate total vendors
        $totalVendors = Vendor::count();

        // Calculate vendors registered in the last 30 days
        $last30DaysVendors = Vendor::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        // Calculate vendors registered in the last week
        $lastWeekVendors = Vendor::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        return [
            Stat::make('Total Vendors', $totalVendors)
                ->description('All registered vendors')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Last 30 Days', $last30DaysVendors)
                ->description('New registrations in the last month')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),

            Stat::make('Last 7 Days', $lastWeekVendors)
                ->description('New registrations this week')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),
        ];
    }
}
