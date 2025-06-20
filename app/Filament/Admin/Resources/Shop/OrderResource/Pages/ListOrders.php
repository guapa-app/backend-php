<?php

namespace App\Filament\Admin\Resources\Shop\OrderResource\Pages;

use App\Enums\OrderStatus;
use App\Filament\Admin\Resources\Shop\OrderResource;
use App\Filament\Admin\Resources\Shop\OrderResource\Widgets\OrdersStatusChart;
use App\Filament\Admin\Resources\Shop\OrderResource\Widgets\OrderStats;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('clear_filters')
                ->label('Clear Filters')
                ->icon('heroicon-o-x-mark')
                ->color('gray')
                ->action(function () {
                    // Clear all table filters by redirecting to the same page without query parameters
                    $this->redirect(request()->url());
                })
                ->visible(fn () => request()->hasAny(['tableFilters.vendor', 'tableFilters.category'])),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderStats::make(),
            OrdersStatusChart::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->query(fn ($query) => $query->where('status', '!=', OrderStatus::Expired)),
            'pending' => Tab::make('Pending')
                ->query(fn ($query) => $query->where('status', OrderStatus::Pending)),
            'accepted' => Tab::make('Accepted')
                ->query(fn ($query) => $query->where('status', OrderStatus::Accepted)),
            'used' => Tab::make('Used')
                ->query(fn ($query) => $query->where('status', OrderStatus::Used)),
            'rejected' => Tab::make('Rejected')
                ->query(fn ($query) => $query->where('status', OrderStatus::Rejected)),
            'canceled' => Tab::make('Canceled')
                ->query(fn ($query) => $query->where('status', OrderStatus::Canceled)),
        ];
    }

}
