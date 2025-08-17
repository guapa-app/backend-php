<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Calculate total users
        $totalUsers = User::count();

        // Calculate users by gender
        $maleUsers = User::gender('Male')->count();
        $femaleUsers = User::gender('Female')->count();

        // unspecified gender
        $unspecifiedGenderUsers = User::whereDoesntHave('profile')->orWhereHas('profile', function ($query) {
            $query->where('gender', null);
        })->count();

        // Calculate users registered in the last 30 days
        $last30DaysUsers = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        // Calculate users registered in the last week
        $lastWeekUsers = User::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        return [
            Stat::make('Total Users', $totalUsers)
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Gender Distribution', $maleUsers . ' / ' . $femaleUsers)
                ->description('Male / Female users')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Unspecified Gender', $unspecifiedGenderUsers)
                ->description('Users with unspecified gender')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('danger'),

            Stat::make('Last 30 Days', $last30DaysUsers)
                ->description('New registrations in the last month')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),

            Stat::make('Last 7 Days', $lastWeekUsers)
                ->description('New registrations this week')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),
        ];
    }
}
