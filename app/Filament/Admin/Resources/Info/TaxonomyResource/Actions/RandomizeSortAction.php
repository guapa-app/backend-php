<?php

namespace App\Filament\Admin\Resources\Info\TaxonomyResource\Actions;

use App\Models\Taxonomy;
use Filament\Tables\Actions\Action;

class RandomizeSortAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Randomize Sort')
            ->icon('heroicon-o-numbered-list')
            ->color('primary')
            ->requiresConfirmation()
            ->action(function () {
                $uniqueSortOrders = range(1, Taxonomy::count());
                shuffle($uniqueSortOrders);

                Taxonomy::all()->each(function ($product) use (&$uniqueSortOrders) {
                    $product->sort_order = array_pop($uniqueSortOrders);
                    $product->save();
                });
            })
            ->modalSubmitActionLabel('Randomize Sort');
    }
}
