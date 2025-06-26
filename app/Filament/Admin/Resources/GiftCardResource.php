<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\GiftCard;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Http\Request;
use App\Exports\GiftCardExport;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Forms\Components\DateTimePicker;
use Filament\Actions\Exports\ExportBulkAction;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

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
                            ->minValue(\App\Models\GiftCardSetting::getMinAmount())
                            ->maxValue(\App\Models\GiftCardSetting::getMaxAmount())
                            ->helperText('Amount between ' . \App\Models\GiftCardSetting::getMinAmount() . ' and ' . \App\Models\GiftCardSetting::getMaxAmount()),

                        Select::make('currency')
                            ->options(\App\Models\GiftCardSetting::getSupportedCurrencies())
                            ->label('Currency')
                            ->required()
                            ->default(\App\Models\GiftCardSetting::getDefaultCurrency()),
                    ]),
                ]),

            // Order Details Section (only for order type)
            Section::make('Order Details')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('vendor_id')
                            ->label('Vendor')
                            ->relationship('vendor', 'name', function ($query) {
                                return $query->whereNotNull('name')->where('name', '!=', '');
                            })
                            ->searchable()
                            ->preload()
                            ->visible(fn($get) => $get('gift_type') === GiftCard::GIFT_TYPE_ORDER)
                            ->required(fn($get) => $get('gift_type') === GiftCard::GIFT_TYPE_ORDER),

                        Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'title', function ($query) {
                                return $query->whereNotNull('title')->where('title', '!=', '');
                            })
                            ->searchable()
                            ->preload()
                            ->visible(fn($get) => $get('gift_type') === GiftCard::GIFT_TYPE_ORDER)
                            ->required(fn($get) => $get('gift_type') === GiftCard::GIFT_TYPE_ORDER),
                    ]),

                    Select::make('offer_id')
                        ->label('Offer')
                        ->relationship('offer', 'title', function ($query) {
                            return $query->whereNotNull('title')->where('title', '!=', '');
                        })
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
                        ->relationship('user', 'name', function ($query) {
                            return $query->whereNotNull('name')->where('name', '!=', '');
                        })
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
                            ->options(array_combine(\App\Models\GiftCardSetting::getBackgroundColors(), \App\Models\GiftCardSetting::getBackgroundColors()))
                            ->searchable()
                            ->helperText('Choose a solid color background'),

                        Select::make('background_image_id')
                            ->label('Background Image')
                            ->relationship('backgroundImage', 'name', function ($query) {
                                return $query->whereNotNull('name')->where('name', '!=', '');
                            })
                            ->searchable()
                            ->preload()
                            ->helperText('Choose from admin-uploaded backgrounds'),
                    ]),

                    SpatieMediaLibraryFileUpload::make('background_image')
                        ->collection('gift_card_backgrounds')
                        ->label('Upload Custom Background')
                        ->maxFiles(1)
                        ->acceptedFileTypes(\App\Models\GiftCardSetting::getAllowedFileTypes())
                        ->maxSize(\App\Models\GiftCardSetting::getMaxFileSize() / 1024)
                        ->helperText('Upload a custom background image (max ' . (\App\Models\GiftCardSetting::getMaxFileSize() / 1024 / 1024) . 'MB)'),
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

                // Additional columns for better admin view
                TextColumn::make('redemption_method')
                    ->label('Redemption')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'wallet',
                        'primary' => 'order',
                    ])
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('redeemed_at')
                    ->label('Redeemed')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('vendor.name')
                    ->label('Vendor')
                    ->searchable()
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

                SelectFilter::make('redemption_method')
                    ->label('Redemption Method')
                    ->options([
                        'pending' => 'Pending',
                        'wallet' => 'Wallet',
                        'order' => 'Order',
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

                Filter::make('date_range')
                    ->form([
                        DatePicker::make('date_from')->label('Date From'),
                        DatePicker::make('date_to')->label('Date To'),
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['date_from']) {
                            $query->whereDate('created_at', '>=', $data['date_from']);
                        }
                        if ($data['date_to']) {
                            $query->whereDate('created_at', '<=', $data['date_to']);
                        }
                    }),

                TernaryFilter::make('is_expired')
                    ->label('Expired')
                    ->queries(
                        true: fn ($query) => $query->where('expires_at', '<', now()),
                        false: fn ($query) => $query->where('expires_at', '>=', now())->orWhereNull('expires_at'),
                        blank: fn ($query) => $query,
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn (GiftCard $record): string => route('filament.admin.resources.gift-cards.preview', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['status' => GiftCard::STATUS_ACTIVE]);
                            });
                        })
                        ->requiresConfirmation(),

                    BulkAction::make('expire')
                        ->label('Mark as Expired')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['status' => GiftCard::STATUS_EXPIRED]);
                            });
                        })
                        ->requiresConfirmation(),

                    BulkAction::make('cancel')
                        ->label('Cancel')
                        ->icon('heroicon-o-x-mark')
                        ->color('secondary')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['status' => GiftCard::STATUS_CANCELLED]);
                            });
                        })
                        ->requiresConfirmation(),

                    BulkAction::make('export_selected')
                        ->label('Export Selected')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($records) {
                            return Excel::download(
                                new GiftCardExport([], null, $records),
                                'selected-gift-cards-' . now()->format('Y-m-d-H-i-s') . '.xlsx'
                            );
                        }),

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
            'preview' => \App\Filament\Admin\Resources\GiftCardResource\Pages\PreviewGiftCard::route('/{record}/preview'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Export Gift Cards')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function (Request $request) {
                    $filters = $request->only(['gift_type', 'status', 'redemption_method', 'search', 'vendor_id', 'user_id']);
                    $dateRange = $request->only(['date_from', 'date_to']);

                    return Excel::download(
                        new GiftCardExport($filters, $dateRange),
                        'gift-cards-' . now()->format('Y-m-d-H-i-s') . '.xlsx'
                    );
                })
                ->form([
                    \Filament\Forms\Components\Select::make('gift_type')
                        ->label('Gift Type')
                        ->options([
                            'wallet' => 'Wallet',
                            'order' => 'Order',
                        ])
                        ->placeholder('All Types'),

                    \Filament\Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'active' => 'Active',
                            'used' => 'Used',
                            'expired' => 'Expired',
                            'cancelled' => 'Cancelled',
                        ])
                        ->placeholder('All Statuses'),

                    \Filament\Forms\Components\Select::make('redemption_method')
                        ->label('Redemption Method')
                        ->options([
                            'pending' => 'Pending',
                            'wallet' => 'Wallet',
                            'order' => 'Order',
                        ])
                        ->placeholder('All Methods'),

                    \Filament\Forms\Components\TextInput::make('search')
                        ->label('Search')
                        ->placeholder('Search by code, name, or email'),

                    \Filament\Forms\Components\DatePicker::make('date_from')
                        ->label('Date From'),

                    \Filament\Forms\Components\DatePicker::make('date_to')
                        ->label('Date To'),
                ]),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            \App\Filament\Admin\Widgets\GiftCardStatsWidget::class,
            \App\Filament\Admin\Widgets\GiftCardTrendsWidget::class,
        ];
    }
}
