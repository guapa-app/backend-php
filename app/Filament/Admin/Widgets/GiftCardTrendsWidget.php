<?php

namespace App\Filament\Admin\Widgets;

use App\Models\GiftCard;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;

class GiftCardTrendsWidget extends ChartWidget
{
    protected static ?string $heading = 'Gift Card Trends';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $days = collect();
        $createdData = collect();
        $redeemedData = collect();

        // Get data for last 30 days
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days->push($date->format('M d'));

            // Gift cards created on this day
            $created = GiftCard::whereDate('created_at', $date)->count();
            $createdData->push($created);

            // Gift cards redeemed on this day
            $redeemed = GiftCard::whereDate('redeemed_at', $date)->count();
            $redeemedData->push($redeemed);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Gift Cards Created',
                    'data' => $createdData->toArray(),
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Gift Cards Redeemed',
                    'data' => $redeemedData->toArray(),
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $days->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
        ];
    }
}
