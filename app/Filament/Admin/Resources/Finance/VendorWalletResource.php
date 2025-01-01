<?php

namespace App\Filament\Admin\Resources\Finance;

use App\Filament\Admin\Resources\Finance\VendorWalletResource\Pages;
use App\Models\Wallet;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VendorWalletResource extends Resource
{
    protected static ?string $model = Wallet::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Finance';


    public static function getLabel(): string
    {
        return 'Vendor Wallet';
    }

    public static function getPluralLabel(): string
    {
        return 'Vendor Wallets';
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
           ->whereNotNull('vendor_id')->where('balance', '>', 0);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vendor.name')
                    ->label('Vendor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('balance')
                    ->money()
                    ->sortable(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVendorWallets::route('/'),
        ];
    }
}
