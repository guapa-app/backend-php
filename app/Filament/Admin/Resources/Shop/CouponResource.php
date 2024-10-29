<?php

namespace App\Filament\Admin\Resources\Shop;

use App\Filament\Admin\Resources\Shop\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->disabled(fn (?Model $record) => $record !== null)
                    ->rules([
                        'required',
                        'max:12',
                        Rule::unique('coupons', 'code')->ignore(fn ($record) => $record->id),
                    ]),
                Forms\Components\TextInput::make('discount_percentage')
                    ->disabled(fn (?Model $record) => $record !== null)
                    ->rules(['required', 'numeric', 'min:0', 'max:100'])
                    ->minValue(0)
                    ->numeric(),
                Forms\Components\Select::make('discount_source')
                    ->options([
                        'vendor' => 'Vendor',
                        'app' => 'Guapa',
                        'both' => 'Both',
                    ])->native(false)
                    ->required(),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->minDate(now())
                    ->rules('required', 'after:today'),
                Forms\Components\TextInput::make('max_uses')
                    ->numeric()
                    ->minValue(0)
                    ->default(1),
                Forms\Components\TextInput::make('single_user_usage')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(1),
                Forms\Components\Select::make('admin_id')
                    ->relationship('admin', 'name')
                    ->native(false)
                    ->hidden()
                    ->default(auth()->id()),

                Forms\Components\Fieldset::make('Related To')
                    ->columns(1)
                    ->schema([
                        Forms\Components\Select::make('Products')
                            ->relationship('Products', 'title')
                            ->preload()
                            ->multiple(),

                        Forms\Components\Select::make('Vendors')
                            ->relationship('Vendors', 'name')
                            ->preload()
                            ->multiple(),

                        Forms\Components\Select::make('Categories')
                            ->relationship('categories', 'title', function (Builder $query) {
                                return $query->whereIn('type', ['specialty', 'category']);
                            })
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->title}")
                            ->preload()
                            ->multiple(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('discount_percentage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_source'),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_uses')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('admin.name'),
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
