<?php

namespace App\Filament\Resources\Shop\OrderResource\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\Shop\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;


class ListOrders extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = OrderResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),

        ];
    }
    protected function getTableActions(): array
    {
        return [

        ];
    }
    protected function getHeaderWidgets(): array
    {
        return OrderResource::getWidgets();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->query(fn ($query) => $query->where('status','!=', OrderStatus::Expired)),
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
