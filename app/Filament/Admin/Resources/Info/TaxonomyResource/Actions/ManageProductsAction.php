<?php

namespace App\Filament\Admin\Resources\Info\TaxonomyResource\Actions;

use App\Models\Product;
use Filament\Forms;
use Filament\Pages\Actions\ButtonAction;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;

class ManageProductsAction extends ViewAction
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->label('Manage Products');
        // Set modal properties
        $this->modalHeading('Manage Products')
            ->modalWidth('4xl')
            ->form(function ($record) {
                return [
                    Forms\Components\Repeater::make('existingProducts')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Product Name')
                                ->disabled(),
                            Forms\Components\Hidden::make('id'),
//                            Forms\Components\Fieldset::make('Actions')
//                                ->schema([
//
//                                ]),
                        ])
                        ->label('Existing Products')
                        ->default($record->products()->get()->toArray())
                      ,

                    Forms\Components\Select::make('newProduct')
                        ->label('Attach Product')
                        ->options(
                            Product::whereNotIn('id', $record->products()->pluck('id'))
                                ->pluck('title', 'id')
                        )
                        ->searchable(),
                ];
            })
            ->action(function ($record, $data) {
                if (isset($data['newProduct'])) {
                    $this->attachProduct($record, $data['newProduct']);
                }
            });
    }

    protected function attachProduct($record, $productId): void
    {
        $record->products()->attach($productId); // Add product to the category
    }

    protected function removeProduct($record, $productId): void
    {
        $record->products()->detach($productId); // Remove product from the category
    }
}
