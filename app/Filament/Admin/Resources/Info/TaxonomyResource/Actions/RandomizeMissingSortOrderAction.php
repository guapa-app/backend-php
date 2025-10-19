<?php

namespace App\Filament\Admin\Resources\Info\TaxonomyResource\Actions;

use App\Models\Taxonomy;
use Filament\Tables\Actions\Action;

class RandomizeMissingSortOrderAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Randomize Missing Sort Order')
            ->icon('heroicon-o-arrow-path')
            ->requiresConfirmation()
            ->color('indigo')
            ->action(function () {
                $productCount = Taxonomy::count();

                $existingSortOrders = Taxonomy::whereNotNull('sort_order')->pluck('sort_order')->toArray();

                $productsWithoutSortOrder = Taxonomy::whereNull('sort_order')->get();

                $availableSortOrders = range(1, $productCount);

                $availableSortOrders = array_diff($availableSortOrders, $existingSortOrders);

                shuffle($availableSortOrders);

                $productsWithoutSortOrder->each(function ($product) use (&$availableSortOrders) {
                    $product->sort_order = array_pop($availableSortOrders);
                    $product->save();
                });
            })
            ->modalSubmitActionLabel('Randomize Missing Sort Order');
    }
}
