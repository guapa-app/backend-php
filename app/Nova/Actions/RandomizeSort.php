<?php

namespace App\Nova\Actions;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class RandomizeSort extends Action
{
    use InteractsWithQueue, Queueable;

    public function handle(ActionFields $fields, Collection $models)
    {
        $uniqueSortOrders = range(1, Product::count());
        shuffle($uniqueSortOrders);

        Product::all()->each(function ($product) use (&$uniqueSortOrders) {
            $product->sort_order = array_pop($uniqueSortOrders);
            $product->save();
        });

        return Action::message('Sort order randomized for all products.');
    }

    /**
     * Get the fields available on the action.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }
}
