<?php

namespace App\Filament\Admin\Resources\Shop;

use Filament\Forms;
use Filament\Tables;
use App\Models\Vendor;
use App\Helpers\Common;
use App\Models\Country;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\ProductReview;
use App\Enums\ProductStatus;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\Shop\ProductResource\Pages;
use App\Filament\Admin\Resources\Shop\ProductResource\Actions;
use App\Filament\Admin\Resources\Shop\ProductResource\RelationManagers;

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
                Forms\Components\Hidden::make('type')
                    ->default(request('type')),
                Forms\Components\Select::make('country_id')
                    ->label('Country')
                    ->required()
                    ->options(Country::query()->pluck('name', 'id'))
                    ->searchable()
                    ->reactive() // Makes the field reactive to changes
                    ->afterStateUpdated(function (callable $set, $state) {
                        // Reset the vendor_id field when the country changes
                        $set('vendor_id', null);
                    }),
                Forms\Components\Select::make('vendor_id')
                    ->label('Vendor')
                    ->required()
                    ->options(function ($get) {
                        $countryId = $get('country_id'); // Get the selected country_id
                        if ($countryId) {
                            // Fetch vendors for the selected country
                            return Vendor::where('country_id', $countryId)
                                ->pluck('name', 'id');
                        }
                        return []; // Return an empty list if no country is selected
                    })
                    ->searchable()
                    ->reactive(), // Refreshes options when dependent state changes
                Forms\Components\TextInput::make('sort_order')
                    ->numeric(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('taxonomies')
                    ->label(__('Categories'))
                    ->relationship(
                        name: 'taxonomies',
                        modifyQueryUsing: fn(Builder $query, $record) => ($record->type?->value ?? request('type')) == 'service' ?
                            $query->where('type', 'specialty') :
                            $query->where('type', 'category')
                    )
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->title}")
                    ->required(),
                // Forms\Components\Select::make('vendor_id')
                //     ->label(__('Vendor'))
                //     ->native(false)
                //     ->relationship(name: 'vendor')
                //     ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->name}")
                //     ->required(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('earned_points')
                    ->label('Points')
                    ->minValue(0)
                    ->numeric(),
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
                Tables\Columns\TextColumn::make('country.name')->label('Country'),
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
                Tables\Columns\TextColumn::make('earned_points')
                    ->label('Points'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                Actions\RandomizeMissingSortOrderAction::make('randomize-missing-sort'),
                Actions\RandomizeSortAction::make('randomize-sort'),
                Actions\ClearSortAction::make('clear-sort'),
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
