<?php

namespace App\Filament\Resources\Shop;

use App\Enums\ProductReview;
use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Filament\Resources\Shop\ProductResource\Pages;
use App\Models\Product;
use App\Traits\FilamentVendorAccess;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\Shop\ProductResource\RelationManagers;
use App\Helpers\Common;

class ProductResource extends Resource
{
    use FilamentVendorAccess;

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('hash_id')
                    ->disabled()
                    ->dehydrated(true)
                    ->default(Common::generateUniqueHashForModel(self::$model, 16))
                    ->maxLength(16),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\Select::make('review')
                    ->options(ProductReview::class)
                    ->default(ProductReview::Pending)
                    ->disabled()
                    ->dehydrated(true),
                Forms\Components\Select::make('status')
                    ->options(ProductStatus::class)
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options(ProductType::class)
                    ->required(),
                Forms\Components\Textarea::make('terms')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('url')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('review')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OfferRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
