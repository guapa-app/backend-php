<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderUpdatedNotification;

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
    public function fields(NovaRequest $request)
    {
        return [
            Select::make(__('status'), 'status')
                ->default(2)
                ->options([
                    'Pending' => 'Pending',
                    'Accepted' => 'Accepted',
                    'Completed' => 'Completed',
                    'Canceled' => 'Canceled',
                    'Rejected' => 'Rejected',
                ])
                ->displayUsingLabels()
                ->required(),
        ];
    }
}
