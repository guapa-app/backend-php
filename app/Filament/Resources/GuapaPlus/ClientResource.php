<?php

namespace App\Filament\Resources\GuapaPlus;

use App\Filament\Resources\GuapaPlus\ClientResource\Pages;
use App\Filament\Resources\GuapaPlus\Widgets\ClientOrdersWidget;
use App\Models\VendorClient;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClientResource extends Resource
{
    protected static ?string $model = VendorClient::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Guapa Plus';
    protected static ?string $modelLabel = 'Client';
    protected static ?string $pluralModelLabel = 'Clients';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.phone')->label('Phone Number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.orders_count')
                    ->label('Orders Count'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            ClientOrdersWidget::class,
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $vendorId = auth()->user()->userVendors->first()->vendor_id;

        return parent::getEloquentQuery()
            ->where('vendor_id', $vendorId)
            ->with(['user' => function ($query) use ($vendorId) {
                $query->select('id', 'name', 'email', 'phone')
                    ->withCount(['orders' => function ($query) use ($vendorId) {
                        $query->where('vendor_id', $vendorId);
                    }]);
            }]);
    }

    public static function getBulkUploadFormSchema(): array
    {
        return [
            FileUpload::make('excel_file')
                ->label('Upload Excel File')
                ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                ->required(),
            View::make('filament.components.clients-sheet-download-link')
                ->label('Download Example Sheet'),
        ];
    }
}
