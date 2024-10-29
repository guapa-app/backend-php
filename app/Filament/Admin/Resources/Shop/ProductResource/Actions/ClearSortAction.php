<?php

namespace App\Filament\Admin\Resources\Shop\ProductResource\Actions;

use App\Models\Product;
use Filament\Tables\Actions\Action;

class ClearSortAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Clear Sort')
            ->icon('heroicon-o-x-circle')
            ->requiresConfirmation()
            ->action(fn() => Product::query()->update(['sort_order' => null]))
            ->modalSubmitActionLabel('Clear Sort');
    }
}
