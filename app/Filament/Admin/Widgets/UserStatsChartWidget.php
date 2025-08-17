<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UserStatsChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Total Users by Month';

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
        $usersData = User::select(
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
        foreach ($usersData as $user) {
            $monthlyCounts[$user->month] = $user->count;
        }

        // Build the dataset for the chart
        $dataset = [
            'label' => 'Total Users',
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
