<?php

namespace App\Filament\AffiliateMarketeer\Widgets;

use Filament\Widgets\ChartWidget;

class TotalPointsChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
