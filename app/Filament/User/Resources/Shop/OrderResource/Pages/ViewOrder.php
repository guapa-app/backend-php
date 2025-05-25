<?php

namespace App\Filament\User\Resources\Shop\OrderResource\Pages;

use App\Services\NotificationInterceptor;

use App\Enums\OrderStatus;
use App\Enums\ProductType;
use App\Filament\User\Resources\Shop\OrderResource;
use App\Notifications\OrderUpdatedNotification;
use Filament\Actions;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Notification;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        $order = $this->getRecord();

        $actions = [];

        if ($order->status === OrderStatus::Pending) {
            $hasProductItems = $order->items->contains(function ($item) {
                return $item->product->type == ProductType::Product;
            });

            if ($hasProductItems) {
                $actions[] = Actions\Action::make('approve')
                    ->label('Approve Order')
                    ->action(fn () => $this->processOrder('approve'));

                $actions[] = Actions\Action::make('reject')
                    ->label('Reject Order')
                    ->action(fn () => $this->processOrder('reject'));
            }
        }

        return $actions;
    }

    public function processOrder(string $action)
    {
        $order = $this->getRecord();
        $status = $action === 'approve' ? OrderStatus::Accepted : OrderStatus::Rejected;
        $order->status = $status;
        $order->save();

        app(\App\Services\NotificationInterceptor::class)->interceptSingle($order->user, new OrderUpdatedNotification($order));

        $message = $action === 'approve' ? 'Order approved successfully.' : 'Order rejected successfully.';

        FilamentNotification::make()
            ->title($message)
            ->success()
            ->send();
    }

    public function getRecordTitle(): string
    {
        $order = $this->getRecord();

        return 'Order #' . $order->id;
    }
}
