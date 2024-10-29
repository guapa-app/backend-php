<?php

namespace App\Filament\Admin\Resources\Shop\ProductResource\Actions;

use App\Models\Product;
use Filament\Tables\Actions\Action;

class RandomizeSortAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Randomize Sort')
            ->icon('heroicon-o-numbered-list')
            ->requiresConfirmation()
            ->action(function () {
                $uniqueSortOrders = range(1, Product::count());
                shuffle($uniqueSortOrders);

                Product::all()->each(function ($product) use (&$uniqueSortOrders) {
                    $product->sort_order = array_pop($uniqueSortOrders);
                    $product->save();
                });
            })
            ->modalSubmitActionLabel('Randomize Sort');
    }
}
