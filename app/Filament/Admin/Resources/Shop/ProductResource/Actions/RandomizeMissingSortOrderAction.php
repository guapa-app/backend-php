<?php

namespace App\Filament\Admin\Resources\Shop\ProductResource\Actions;

use App\Models\Product;
use Filament\Tables\Actions\Action;

class RandomizeMissingSortOrderAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Randomize Missing Sort Order')
            ->icon('heroicon-o-arrow-path')
            ->requiresConfirmation()
            ->color('warning')
            ->action(function () {
                $productCount = Product::count();

                $existingSortOrders = Product::whereNotNull('sort_order')->pluck('sort_order')->toArray();

                $productsWithoutSortOrder = Product::whereNull('sort_order')->get();

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
