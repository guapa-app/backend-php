<?php

namespace App\Filament\Admin\Resources\Shop;

use App\Enums\OrderStatus;
use App\Filament\Admin\Resources\Shop\CouponResource\Pages;
use App\Filament\Admin\Resources\Shop\CouponResource\RelationManagers\OrdersRelationManager;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Filament\Infolists\Infolist;
use Filament\Infolists;

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
                    ->rules(function (?Model $record) {
                        return [
                            'required',
                            'max:12',
                            Rule::unique('coupons', 'code')
                                ->when($record !== null, fn ($query) => $query->ignore($record->id)),
                        ];
                    }),
                Forms\Components\Select::make('discount_source')
                    ->options([
                        'vendor' => 'Vendor',
                        'app' => 'Guapa',
                        'both' => 'Both',
                    ])->native(false)
                    ->required(),

                Forms\Components\Select::make('type')
                    ->native(false)
                    ->options([
                        'fixed' => 'Fixed',
                        'cashback' => 'Cashback',
                    ])
                    ->reactive()
                    ->required(),

                Forms\Components\TextInput::make('discount_percentage')
                    ->disabled(fn (?Model $record) => $record !== null)
                    ->rules(['numeric', 'min:0', 'max:100'])
                    ->required()
                    ->minValue(0)
                    ->numeric(),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->minDate(now())
                    ->required(fn (Forms\Get $get) => $get('type') === 'fixed')
                    ->rules( 'after:today'),

                Forms\Components\DateTimePicker::make('points_expire_at')
                    ->minDate(now())
                    ->rules( 'after:today')
                    ->required(fn (Forms\Get $get) => $get('type') === 'cashback'),

                Forms\Components\TextInput::make('max_uses')
                    ->numeric()
                    ->minValue(0)
                    ->default(1),
                Forms\Components\TextInput::make('single_user_usage')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(1),
                Forms\Components\Hidden::make('admin_id')
                    ->default(auth()->id()),

                Forms\Components\Fieldset::make('Related To')
                    ->columns(1)
                    ->schema([
                        // Forms\Components\Fieldset::make('Product Selection')
                        //     ->columns(3)
                        //     ->schema([
                        //         Forms\Components\Select::make('vendor_filter')
                        //             ->label('Vendor')
                        //             ->searchable()
                        //             ->options(function () {
                        //                 return \App\Models\Vendor::where('status', '1') // Only active vendors
                        //                     ->pluck('name', 'id')
                        //                     ->toArray();
                        //             })
                        //             ->preload()
                        //             ->live()
                        //             ->afterStateUpdated(function (Forms\Set $set) {
                        //                 $set('address_filter', null);
                        //                 $set('Products', []);
                        //             }),
        
                        //         Forms\Components\Select::make('address_filter')
                        //             ->label('Sub-Vendor')
                        //             ->searchable()
                        //             ->options(function (Forms\Get $get) {
                        //                 $vendorId = $get('vendor_filter');
                        //                 if (!$vendorId) {
                        //                     return [];
                        //                 }
                                        
                        //                 return \App\Models\Address::where('addressable_id', $vendorId)
                        //                     ->where('addressable_type', 'vendor')
                        //                     ->get()
                        //                     ->mapWithKeys(function ($address) {
                        //                         $cityName = $address->city?->name ?? 'Unknown City';
                        //                         $addressText = $address->address_1 ?? 'No Address';
                        //                         return [$address->id => "{$cityName} - {$addressText}"];
                        //                     });
                        //             })
                        //             ->live()
                        //             ->afterStateUpdated(function (Forms\Set $set) {
                        //                 $set('Products', []);
                        //             })
                        //             ->disabled(fn (Forms\Get $get) => !$get('vendor_filter')),
        
                        //         Forms\Components\Select::make('Products')
                        //             ->relationship('Products', 'title', function (Builder $query, Forms\Get $get) {
                        //                 $addressId = $get('address_filter');
                        //                 $vendorId = $get('vendor_filter');
                        //                 $query->where('vendor_id', $vendorId);
                        //                 if ($addressId) {
                        //                     return $query->whereHas('addresses', function ($q) use ($addressId) {
                        //                         $q->where('addresses.id', $addressId);
                        //                     });
                        //                 }
                        //                 return $query->whereRaw('1 = 0'); // Return empty by default
                        //             })
                        //             ->getOptionLabelFromRecordUsing(function (Model $record) {
                        //                 $vendorName = $record->vendor?->name ?? '';
                        //                 return "{$record->title}" . ($vendorName ? " - {$vendorName}" : '');
                        //             })
                        //             ->searchable()
                        //             ->preload()
                        //             ->multiple()
                        //             ->disabled(fn (Forms\Get $get) => !$get('address_filter')),
                        //     ]),

                        Forms\Components\Select::make('Products')
                            ->relationship('Products', 'title', function (Builder $query) {
                                return $query->whereHas('vendor', function (Builder $query) {
                                    $query->where('status', '1');
                                });
                            })
                            ->getOptionLabelFromRecordUsing(function (Model $record) {
                                $vendorName = $record->vendor?->name ?? '';
                                return "{$record->title}" . ($vendorName ? " - {$vendorName}" : '');
                            })
                            ->preload()
                            ->searchable()
                            ->multiple(),

                        Forms\Components\Select::make('Vendors')
                            ->relationship('Vendors', 'name', function (Builder $query) {
                                return $query->where('status', '1'); // Only active vendors
                            })
                            ->searchable()
                            ->preload()
                            ->multiple(),

                        Forms\Components\Select::make('Categories')
                            ->relationship('categories', 'title', function (Builder $query) {
                                return $query->whereIn('type', ['specialty', 'category']);
                            })
                            ->searchable()
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->title}")
                            ->preload()
                            ->multiple(),
                    ]),


                Forms\Components\Select::make('Assign To')
                    ->relationship('affiliate_marketeer', 'name')
                    ->searchable()
                    ->options(
                        \App\Models\User::whereHas('roles', function (Builder $query) {
                            $query->where('name', 'affiliate_marketeer');
                        })->pluck('name', 'id')
                    )
                    ->columnSpanFull()
                    ->preload()
                    ->multiple(),
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
                Tables\Columns\TextColumn::make('single_user_usage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('usage_count')
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Coupon Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('code')
                            ->label('Coupon Code')
                            ->columnSpan(1),


                        Infolists\Components\TextEntry::make('type')
                            ->label('Coupon Type')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'fixed' => 'warning',
                                'cashback' => 'success',
                            })
                            ->columnSpan(1),

                        Infolists\Components\TextEntry::make('discount_source')
                            ->label('Discount Source')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'vendor' => 'warning',
                                'app' => 'success',
                                'both' => 'info',
                            })
                            ->columnSpan(1),

                        Infolists\Components\TextEntry::make('discount_percentage')
                            ->label('Discount')
                            ->suffix('%')
                            ->columnSpan(1),

                        Infolists\Components\TextEntry::make('expires_at')
                            ->label('Expires At')
                            ->dateTime()
                            ->columnSpan(1),

                        Infolists\Components\TextEntry::make('points_expire_at')
                            ->label('Points Expire At')
                            ->dateTime()
                            ->columnSpan(1),

                        Infolists\Components\TextEntry::make('max_uses')
                            ->label('Max Uses')
                            ->columnSpan(1),

                        Infolists\Components\TextEntry::make('single_user_usage')
                            ->label('Single User Usage')
                            ->columnSpan(1),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make('Usage Statistics')
                    ->schema([
                        Infolists\Components\TextEntry::make('usage_count')
                            ->label('Total Uses')
                            ->state(fn (Model $record): string =>
                                "{$record->usage_count} / " . ($record->max_uses ?: '∞'))
                            ->columnSpan(1),
                        Infolists\Components\TextEntry::make('orders_count')
                            ->label('Total Orders')
                            ->state(fn (Model $record): int =>
                            $record->orders()->count())
                            ->columnSpan(1),
                        Infolists\Components\TextEntry::make('total_discount')
                            ->label('Total Discount Amount')
                            ->state(fn (Model $record): string =>
                            number_format($record->orders()->sum('discount_amount'), 2))
                            ->prefix('$')
                            ->columnSpan(1),
                        Infolists\Components\TextEntry::make('total_order_amount')
                            ->label('Total Orders Amount')
                            ->state(fn (Model $record): string =>
                            number_format($record->orders()->sum('total'), 2))
                            ->prefix('$')
                            ->columnSpan(1),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make('Related Items')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('products')
                            ->label('Applicable Products')
                            ->schema([
                                Infolists\Components\TextEntry::make('title')
                            ]),
                        Infolists\Components\RepeatableEntry::make('vendors')
                            ->label('Applicable Vendors')
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                            ]),
                        Infolists\Components\RepeatableEntry::make('categories')
                            ->label('Applicable Categories')
                            ->schema([
                                Infolists\Components\TextEntry::make('title')
                            ]),

                        Infolists\Components\RepeatableEntry::make('affiliate_marketeer')
                            ->label('Assigned To Affiliate Marketeer')
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                            ]),
                    ])
                    ->columns(4),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
            'view' => Pages\ViewCoupon::route('/{record}'),
        ];
    }
}
