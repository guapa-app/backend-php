<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Orders', Order::count())
                ->description('All time orders')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info'),

            Stat::make('Total Amount', Order::sum('total'))
                ->description('All time orders amount')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('info'),

            Stat::make('Pending Orders', Order::where('status', OrderStatus::Pending->value)->count())
                ->description('Orders awaiting processing')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Pending Amount', Order::where('status', OrderStatus::Pending->value)->sum('total'))
                ->description('Orders awaiting processing amount')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'),

            Stat::make('Accepted Orders', Order::where('status', OrderStatus::Accepted->value)->count())
                ->description('Accepted Orders')
                ->descriptionIcon('heroicon-m-clock')
                ->color('success'),

            Stat::make('Accepted Amount', Order::where('status', OrderStatus::Accepted->value)->sum('total'))
                ->description('Accepted Orders amount')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Used Orders', Order::where('status',  OrderStatus::Used->value)->count())
                ->description('Completed orders')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Used Amount', Order::where('status',  OrderStatus::Used->value)->sum('total'))
                ->description('Completed orders amount')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }
}
