<?php

namespace App\Filament\User\Widgets;

use App\Models\Post;
use App\Models\Order;
use App\Models\Product;
use Flowframe\Trend\Trend;
use App\Models\Consultation;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getCards(): array
    {
        $vendor = auth()->user()->userVendors->first()->vendor;

        $products = Trend::query(Product::query()->where('vendor_id', $vendor->id))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        $posts = Trend::query(Post::query()->where('vendor_id', $vendor->id))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        $orders = Trend::query(Order::query()->where('vendor_id', $vendor->id))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        $consultations = Trend::query(Consultation::query()->where('vendor_id', $vendor->id))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            Card::make(__('Products'), $products->sum(fn (TrendValue $value) => $value->aggregate))
                ->description(number_format($products->average(fn (TrendValue $value) => $value->aggregate), 1) . ' ' . __('per month', ['type' => __('product')]))
                ->descriptionIcon('heroicon-s-clock')
                ->chart($products->map(fn (TrendValue $value) => $value->aggregate)->toArray())
                ->color('success'),

            Card::make(__('Posts'), $posts->sum(fn (TrendValue $value) => $value->aggregate))
                ->description(number_format($posts->average(fn (TrendValue $value) => $value->aggregate), 1) . ' ' . __('per month', ['type' => __('post')]))
                ->descriptionIcon('heroicon-s-clock')
                ->chart($posts->map(fn (TrendValue $value) => $value->aggregate)->toArray())
                ->color('danger'),

            Card::make(__('Orders'), $orders->sum(fn (TrendValue $value) => $value->aggregate))
                ->description(number_format($orders->average(fn (TrendValue $value) => $value->aggregate), 1) . ' ' . __('per month', ['type' => __('order')]))
                ->descriptionIcon('heroicon-s-clock')
                ->chart($orders->map(fn (TrendValue $value) => $value->aggregate)->toArray())
                ->color('warning'),
            Card::make(__('Consultations'), $consultations->sum(fn (TrendValue $value) => $value->aggregate))
                ->description(number_format($consultations->average(fn (TrendValue $value) => $value->aggregate), 1) . ' ' . __('per month', ['type' => __('consultation')]))
                ->descriptionIcon('heroicon-s-clock')
                ->chart($consultations->map(fn (TrendValue $value) => $value->aggregate)->toArray())
                ->color('primary'),   
        ];
    }
}
