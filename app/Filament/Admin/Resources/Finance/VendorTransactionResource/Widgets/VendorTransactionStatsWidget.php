<?php

namespace App\Filament\Admin\Resources\Finance\VendorTransactionResource\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Carbon\Carbon;

class VendorTransactionStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    public string $filter = 'all';

    protected function getColumns(): int
    {
        return 3;
    }

    public function getFilters(): ?array
    {
        return [
            'all' => 'All Time',
            'today' => 'Today',
            'week' => 'This Week',
            'month' => 'This Month',
            'last_month' => 'Last Month',
            'quarter' => 'This Quarter',
            'year' => 'This Year',
        ];
    }

    public function updatedFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    protected function getData(): array
    {
        $query = Transaction::whereNotNull('vendor_id');

        $query->when($this->filter === 'today', fn($q) => $q->whereDate('transaction_date', Carbon::today()))
            ->when($this->filter === 'week', fn($q) => $q->whereBetween('transaction_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]))
            ->when($this->filter === 'month', fn($q) => $q->whereBetween('transaction_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]))
            ->when($this->filter === 'last_month', fn($q) => $q->whereBetween('transaction_date', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()]))
            ->when($this->filter === 'quarter', fn($q) => $q->whereBetween('transaction_date', [Carbon::now()->startOfQuarter(), Carbon::now()->endOfQuarter()]))
            ->when($this->filter === 'year', fn($q) => $q->whereBetween('transaction_date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]));

        $totalTransactions = (clone $query)->count();

        $totalCredit = (clone $query)
            ->where('operation', 'vendor_payout')
            ->sum('amount');

        $totalDebit = (clone $query)
            ->where('operation', 'order_payment')
            ->sum('amount');

        return [
            'totalTransactions' => $totalTransactions,
            'totalCredit' => $totalCredit,
            'totalDebit' => $totalDebit,
        ];
    }

    protected function getCards(): array
    {
        $data = $this->getData();

        return [
            Card::make('Total Transactions', $data['totalTransactions'])
                ->description('Number of vendor transactions')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Card::make('Total Credits', number_format($data['totalCredit'], 2))
                ->description('Total vendor payouts')
                ->descriptionIcon('heroicon-m-arrow-up-circle')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Card::make('Total Debits', number_format($data['totalDebit'], 2))
                ->description('Total order payments')
                ->descriptionIcon('heroicon-m-arrow-down-circle')
                ->color('danger')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }
}
