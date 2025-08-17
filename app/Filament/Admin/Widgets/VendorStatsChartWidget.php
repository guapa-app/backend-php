<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Vendor;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class VendorStatsChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Total Vendors by Month';

    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '250px';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $currentYear = Carbon::now()->year;

        // Fetch data grouped by month
        $vendorsData = Vendor::select(
            DB::raw('COUNT(*) as count'),
            DB::raw('MONTH(created_at) as month')
        )
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Initialize monthly counts for all months
        $monthlyCounts = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyCounts[$i] = 0;
        }

        // Populate counts for each month
        foreach ($vendorsData as $vendor) {
            $monthlyCounts[$vendor->month] = $vendor->count;
        }

        // Build the dataset for the chart
        $dataset = [
            'label' => 'Total Vendors',
            'data' => array_values($monthlyCounts),
            'backgroundColor' => '#10B981',
            'borderColor' => '#10B981',
            'borderWidth' => 1,
        ];

        return [
            'datasets' => [$dataset],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }
}
