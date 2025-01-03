<?php

namespace App\Filament\Admin\Resources\Shop\OrderResource\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Orders', Order::count())
                ->description('All time orders')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info'),

            Stat::make('Pending Orders', Order::where('status', OrderStatus::Pending->value)->count())
                ->description('Orders awaiting processing')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Accepted Orders', Order::where('status', OrderStatus::Accepted->value)->count())
                ->description('Accepted Orders')
                ->descriptionIcon('heroicon-m-clock')
                ->color('success'),

            Stat::make('Used Orders', Order::where('status',  OrderStatus::Used->value)->count())
                ->description('Completed orders')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
