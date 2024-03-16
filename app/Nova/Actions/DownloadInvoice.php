<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class DownloadInvoice extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $showOnTableRow = true;

    public function handle(ActionFields $fields, Collection $models)
    {
        // Implement your download logic here
        foreach ($models as $model) {
            return Action::openInNewTab(config('app.url') . '/' . $model->order->hash_id . '/show-invoice');
        }
    }
}
