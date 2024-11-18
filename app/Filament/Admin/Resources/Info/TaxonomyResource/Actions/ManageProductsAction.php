<?php

namespace App\Filament\Admin\Resources\Info\TaxonomyResource\Actions;

use App\Models\Product;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Support\Enums\ActionSize;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class ManageProductsAction extends ViewAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->modalHeading(fn ($record) => "Manage Products - {$record->title}")
            ->modalWidth('7xl')
            ->modalIcon('heroicon-o-shopping-bag')
            ->size(ActionSize::Large)
            ->color('success')
            ->modalDescription(fn ($record) => "Manage products for {$record->type} category")
            ->modalContent(function ($record) {
                return view('filament.admin.actions.manage-products', [
                    'record' => $record,
                    'existingProducts' => $this->getExistingProducts($record),
                    'availableProducts' => $this->getAvailableProducts($record),
                ]);
            })
            ->form([
                Forms\Components\Tabs::make('Products Management')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Existing Products')
                            ->schema([
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\Repeater::make('existingProducts')
                                            ->schema([
                                                Forms\Components\Grid::make(4)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('title')
                                                            ->label('Product Name')
                                                            ->disabled()
                                                            ->columnSpan(1),
                                                        Forms\Components\Hidden::make('id'),
                                                        Forms\Components\Actions::make([
                                                            Forms\Components\Actions\Action::make('remove')
                                                                ->label('Remove')
                                                                ->color('danger')
                                                                ->icon('heroicon-m-trash')
                                                                ->requiresConfirmation()
                                                                ->action(function ($component, $record, $data) {
                                                                    $this->removeProduct($record, $data['id']);
                                                                    Notification::make()
                                                                        ->title('Product removed successfully')
                                                                        ->success()
                                                                        ->send();
                                                                }),
                                                        ])->columnSpan(1),
                                                    ]),
                                            ])
                                            ->label(false)
                                            ->default(fn ($record) => $this->getExistingProducts($record)->toArray())
                                            ->columns(4),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Add New Products')
                            ->schema([
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\Select::make('newProducts')
                                            ->label('Select Products to Attach')
                                            ->multiple()
                                            ->options(function ($record) {
                                                return $this->getAvailableProducts($record);
                                            })
                                            ->searchable(['title'])
                                            ->preload()
                                            ->optionsLimit(50)
                                            ->helperText('Search by product name')
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('title')
                                                    ->required(),
                                            ]),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->action(function ($record, $data) {
                if (!empty($data['newProducts'])) {
                    $this->attachProducts($record, $data['newProducts']);
                    Notification::make()
                        ->title('Products attached successfully')
                        ->success()
                        ->send();
                }
            });
    }

    protected function getExistingProducts($record): Collection
    {
        return $record->products()
            ->select(['id', 'title'])
            ->orderBy('title')
            ->get();
    }

    protected function getAvailableProducts($record): array
    {
        return Product::query()
            ->whereNotIn('id', $record->products()->pluck('id'))
            ->orderBy('title')
            ->pluck('title', 'id')
            ->toArray();
    }

    protected function attachProducts($record, array $productIds): void
    {
        $record->products()->attach($productIds);
    }

    protected function removeProduct($record, $productId): void
    {
        $record->products()->detach($productId);
    }
}
