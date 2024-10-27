<?php

namespace App\Filament\Admin\Resources\Shop;

use App\Enums\ProductReview;
use App\Enums\ProductStatus;
use App\Filament\Admin\Resources\Shop\ProductResource\Pages;
use App\Filament\Admin\Resources\Shop\ProductResource\RelationManagers;
use App\Helpers\Common;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-s-squares-plus';

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Images')
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('media')
                            ->collection('products')
                            ->multiple()
                            ->maxFiles(5)
                            ->hiddenLabel(),
                    ])
                    ->collapsible(),
                Forms\Components\Hidden::make('hash_id')
                    ->label('Number')
                    ->default(Common::generateUniqueHashForModel(self::$model, 16)),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric(),
                Forms\Components\Hidden::make('type')
                    ->default(request('type')),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('taxonomies')
                    ->label(__('Categories'))
                    ->relationship(
                        name: 'taxonomies',
                        modifyQueryUsing: fn (Builder $query, $record) => ($record->type?->value ?? request('type')) == 'service' ?
                            $query->where('type', 'specialty') :
                            $query->where('type', 'category')
                    )
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->title}")
                    ->required(),
                Forms\Components\Select::make('vendor_id')
                    ->label(__('Vendor'))
                    ->native(false)
                    ->relationship(name: 'vendor')
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name}")
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\Select::make('status')
                    ->native(false)
                    ->options(ProductStatus::class)
                    ->required(),
                Forms\Components\Select::make('review')
                    ->native(false)
                    ->options(ProductReview::class)
                    ->required(),
                Forms\Components\TextInput::make('url')
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('terms')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('media')
                    ->label('Image')
                    ->collection('products'),
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vendor.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('review'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            RelationManagers\OffersRelationManager::class,
            RelationManagers\ReviewsRelationManager::class,
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
