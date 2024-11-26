<?php

namespace App\Filament\Admin\Resources\Info\TaxonomyResource\Pages;

use App\Filament\Admin\Resources\Info\TaxonomyResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Forms;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;

class ManageProducts extends Page implements HasTable
{
    use InteractsWithRecord;
    use Tables\Concerns\InteractsWithTable;

    protected static string $view = 'filament.admin.pages.manage-products';
    protected static string $resource = TaxonomyResource::class;

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function attachProduct(array $data): void
    {
        $this->record->products()->attach($data['product_id']);

        Notification::make()
            ->title('Product attached successfully.')
            ->success()
            ->send();
    }

    public function deleteProduct($productId): void
    {
        $this->record->products()->detach($productId);

        Notification::make()
            ->title('Product deleted successfully.')
            ->success()
            ->send();
    }

    protected function getTableQuery(): Builder
    {
        return Product::query()->whereIn('id', $this->record->products()->pluck('id'));
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\CreateAction::make()
                ->label('Attach Product')
                ->form([
                    Forms\Components\Select::make('product_id')
                        ->label('Product')
                        ->options(
                            Product::whereNotIn('id', $this->record->products->pluck('id'))
                                ->pluck('title', 'id')
                        )
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->attachProduct($data);
                }),
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('title')
                ->label('Product Name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('vendor.name')
                ->numeric()
                ->sortable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('delete')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->action(fn ($record) => $this->deleteProduct($record->id)),
        ];
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns())
            ->actions($this->getTableActions())
            ->headerActions($this->getTableHeaderActions());
    }
}
