<?php

namespace App\Filament\Admin\Resources;

use App\Models\GiftCard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

class GiftCardResource extends Resource
{
    protected static ?string $model = GiftCard::class;
    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?string $navigationGroup = 'Shop';
    protected static ?string $navigationLabel = 'Gift Cards';
    protected static ?string $label = 'Gift Card';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Basic Information Section
            Section::make('Basic Information')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('code')
                            ->label('Gift Card Code')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Auto-generated unique code'),

                        Select::make('gift_type')
                            ->label('Gift Card Type')
                            ->options([
                                GiftCard::GIFT_TYPE_WALLET => 'Wallet Credit',
                                GiftCard::GIFT_TYPE_ORDER => 'Order',
                            ])
                            ->required()
                            ->reactive()
                            ->helperText('Choose between wallet credit or specific order'),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->required()
                            ->minValue(1),

                        Select::make('currency')
                            ->options([
                                'SAR' => 'SAR',
                                'USD' => 'USD',
                                'EUR' => 'EUR',
                            ])
                            ->label('Currency')
                            ->required()
                            ->default('SAR'),
                    ]),
                ]),

            // Order Details Section (only for order type)
            Section::make('Order Details')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('vendor_id')
                            ->label('Vendor')
                            ->relationship('vendor', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn($get) => $get('gift_type') === GiftCard::GIFT_TYPE_ORDER)
                            ->required(fn($get) => $get('gift_type') === GiftCard::GIFT_TYPE_ORDER),

                        Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'title')
                            ->searchable()
                            ->preload()
                            ->visible(fn($get) => $get('gift_type') === GiftCard::GIFT_TYPE_ORDER)
                            ->required(fn($get) => $get('gift_type') === GiftCard::GIFT_TYPE_ORDER),
                    ]),

                    Select::make('offer_id')
                        ->label('Offer')
                        ->relationship('offer', 'title')
                        ->searchable()
                        ->preload()
                        ->visible(fn($get) => $get('gift_type') === GiftCard::GIFT_TYPE_ORDER)
                        ->required(fn($get) => $get('gift_type') === GiftCard::GIFT_TYPE_ORDER),
                ])
                ->visible(fn($get) => $get('gift_type') === GiftCard::GIFT_TYPE_ORDER),

            // Recipient Information Section
            Section::make('Recipient Information')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('recipient_name')
                            ->label('Recipient Name')
                            ->required(),

                        TextInput::make('recipient_email')
                            ->label('Recipient Email')
                            ->email(),
                    ]),

                    TextInput::make('recipient_number')
                        ->label('Recipient Phone')
                        ->tel(),

                    Select::make('user_id')
                        ->label('Existing User (Optional)')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->helperText('Select existing user or leave empty for new recipient'),
                ]),

            // Background Customization Section
            Section::make('Background Customization')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('background_color')
                            ->label('Background Color')
                            ->options(array_combine(config('gift_card.colors'), config('gift_card.colors')))
                            ->searchable()
                            ->helperText('Choose a solid color background'),

                        Select::make('background_image_id')
                            ->label('Background Image')
                            ->relationship('backgroundImage', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Choose from admin-uploaded backgrounds'),
                    ]),

                    SpatieMediaLibraryFileUpload::make('background_image')
                        ->collection('gift_card_backgrounds')
                        ->label('Upload Custom Background')
                        ->maxFiles(1)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'])
                        ->maxSize(5120)
                        ->helperText('Upload a custom background image (max 5MB)'),
                ]),

            // Gift Card Details Section
            Section::make('Gift Card Details')
                ->schema([
                    Textarea::make('message')
                        ->label('Message')
                        ->rows(3)
                        ->maxLength(500)
                        ->helperText('Personal message for the recipient'),

                    Textarea::make('notes')
                        ->label('Admin Notes')
                        ->rows(2)
                        ->maxLength(1000)
                        ->helperText('Internal notes (not visible to recipient)'),
                ]),

            // Status & Expiration Section
            Section::make('Status & Expiration')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('status')
                            ->options([
                                GiftCard::STATUS_ACTIVE => 'Active',
                                GiftCard::STATUS_USED => 'Used',
                                GiftCard::STATUS_EXPIRED => 'Expired',
                                GiftCard::STATUS_CANCELLED => 'Cancelled',
                            ])
                            ->label('Status')
                            ->required()
                            ->default(GiftCard::STATUS_ACTIVE),

                        DateTimePicker::make('expires_at')
                            ->label('Expires At')
                            ->native(false)
                            ->helperText('Leave empty for no expiration'),
                    ]),

                    DateTimePicker::make('redeemed_at')
                        ->label('Redeemed At')
                        ->native(false)
                        ->disabled()
                        ->helperText('Auto-filled when gift card is redeemed'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono'),

                BadgeColumn::make('gift_type')
                    ->label('Type')
                    ->colors([
                        'primary' => GiftCard::GIFT_TYPE_WALLET,
                        'success' => GiftCard::GIFT_TYPE_ORDER,
                    ])
                    ->formatStateUsing(fn($state) => $state === GiftCard::GIFT_TYPE_WALLET ? 'Wallet' : 'Order'),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->money(fn($record) => $record->currency)
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => GiftCard::STATUS_ACTIVE,
                        'warning' => GiftCard::STATUS_USED,
                        'danger' => GiftCard::STATUS_EXPIRED,
                        'secondary' => GiftCard::STATUS_CANCELLED,
                    ]),

                TextColumn::make('recipient_name')
                    ->label('Recipient')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('recipient_email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('message')
                    ->label('Message')
                    ->limit(30)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('gift_type')
                    ->label('Gift Type')
                    ->options([
                        GiftCard::GIFT_TYPE_WALLET => 'Wallet',
                        GiftCard::GIFT_TYPE_ORDER => 'Order',
                    ]),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        GiftCard::STATUS_ACTIVE => 'Active',
                        GiftCard::STATUS_USED => 'Used',
                        GiftCard::STATUS_EXPIRED => 'Expired',
                        GiftCard::STATUS_CANCELLED => 'Cancelled',
                    ]),

                Filter::make('amount_range')
                    ->form([
                        TextInput::make('amount_from')->numeric()->label('Amount From'),
                        TextInput::make('amount_to')->numeric()->label('Amount To'),
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['amount_from']) {
                            $query->where('amount', '>=', $data['amount_from']);
                        }
                        if ($data['amount_to']) {
                            $query->where('amount', '<=', $data['amount_to']);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Admin\Resources\GiftCardResource\Pages\ListGiftCards::route('/'),
            'create' => \App\Filament\Admin\Resources\GiftCardResource\Pages\CreateGiftCard::route('/create'),
            'edit' => \App\Filament\Admin\Resources\GiftCardResource\Pages\EditGiftCard::route('/{record}/edit'),
            'view' => \App\Filament\Admin\Resources\GiftCardResource\Pages\ViewGiftCard::route('/{record}'),
        ];
    }
}
