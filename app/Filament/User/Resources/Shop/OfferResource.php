<?php

namespace App\Filament\User\Resources\Shop;

use Filament\Forms;
use Filament\Tables;
use App\Models\Offer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Traits\FilamentVendorAccess;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\User\Resources\Shop\OfferResource\Pages;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class OfferResource extends Resource
{
    use FilamentVendorAccess;

    protected static ?string $model = Offer::class;

    protected static ?string $navigationIcon = 'heroicon-o-fire';

    protected static ?string $navigationGroup = 'Shop';

    public static function canViewAny(): bool
    {
        return (bool) auth()->user()?->Vendor?->isParent();
    }

    public static function canCreate(): bool
    {
        return (bool) auth()->user()?->Vendor?->isParent();

    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('product.vendor', function (Builder $query) {
                $query->where('id', self::authVendorId());
            });
    }

    public static function getEloquentModel(): string
    {
        return Offer::class;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Images')
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                            ->collection('offer_images')
                            ->hiddenLabel(),
                    ])
                    ->collapsible(),
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'title', function (Builder $query) {
                        // Filter products by vendor
                        $vendorId = auth()->user()->userVendors->first()->vendor_id;
                        return $query->where('vendor_id', $vendorId);
                    })
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->suffix('%'),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('terms')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('starts_at')
                    ->required(),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->label('Image')
                    ->collection('offer_images')
                    ->circular(),
                Tables\Columns\TextColumn::make('product.title')
                    ->limit(30)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('discount')
                    ->numeric()
                    ->sortable()
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOffers::route('/'),
            'create' => Pages\CreateOffer::route('/create'),
            'edit' => Pages\EditOffer::route('/{record}/edit'),
        ];
    }
}