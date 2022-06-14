<?php

namespace App\Nova\Actions;

use App\Notifications\OrderUpdatedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

class ChangeOrderStatus extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param ActionFields $fields
     * @param Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $order) {
            $order->update(['status' => $fields->get('status')]);
            Notification::send($order->user, new OrderUpdatedNotification($order));
        }
        return Action::message('Order Changed Status');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Select::make(__('status'), 'status')
                ->default(2)
                ->options([
                    'Pending' => 'Pending',
                    'Accepted' => 'Accepted',
                    'Canceled' => 'Canceled',
                    'Rejected' => 'Rejected',
                ])
                ->displayUsingLabels()
                ->required(),
        ];
    }
}
