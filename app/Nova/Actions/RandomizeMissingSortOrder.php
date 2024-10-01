<?php

namespace App\Nova\Actions;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class RandomizeMissingSortOrder extends Action
{
    use InteractsWithQueue, Queueable;

    public function handle(ActionFields $fields, Collection $models)
    {
        $productCount = Product::count();

        $existingSortOrders = Product::whereNotNull('sort_order')->pluck('sort_order')->toArray();

        $productsWithoutSortOrder = Product::whereNull('sort_order')->get();

        $availableSortOrders = range(1, $productCount);

        $availableSortOrders = array_diff($availableSortOrders, $existingSortOrders);

        shuffle($availableSortOrders);

        $productsWithoutSortOrder->each(function ($product) use (&$availableSortOrders) {
            $product->sort_order = array_pop($availableSortOrders); // Assign a unique sort_order
            $product->save();
        });

        return Action::message('Sort order randomized for reset of un sorted products.');
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
