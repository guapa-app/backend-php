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
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Group;

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
            Forms\Components\TextInput::make('code')->label('Gift Card Code')->hidden(),
            Forms\Components\TextInput::make('amount')->label('Amount')->numeric()->required(),
            Select::make('currency')
                ->options([
                    'SAR' => 'SAR',
                    'USD' => 'USD',
                    'EUR' => 'EUR',
                ])
                ->label('Currency')
                ->required(),
            Select::make('background_color')
                ->options(array_combine(config('gift_card.colors'), config('gift_card.colors')))
                ->searchable()
                ->label('Background Color'),
            SpatieMediaLibraryFileUpload::make('background_image')
                ->collection('gift_card_backgrounds')
                ->label('Background Image')
                ->maxFiles(1)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml']),
            Forms\Components\Textarea::make('message')->label('Message'),
            Select::make('status')
                ->options([
                    \App\Models\GiftCard::STATUS_ACTIVE => 'Active',
                    \App\Models\GiftCard::STATUS_USED => 'Used',
                    \App\Models\GiftCard::STATUS_EXPIRED => 'Expired',
                ])
                ->label('Status')
                ->required(),
            Forms\Components\DateTimePicker::make('expires_at')
                ->label('Expires At')
                ->required()
                ->native(false),
            Forms\Components\DateTimePicker::make('redeemed_at')
                ->label('Redeemed At')
                ->native(false),
            Checkbox::make('create_profile')
                ->label('Create new user profile?')
                ->reactive(),
            Group::make([
                TextInput::make('profile_name')
                    ->label('Recipient Name')
                    ->required(fn($get) => $get('create_profile')),
                TextInput::make('profile_email')
                    ->label('Recipient Email')
                    ->email()
                    ->required(fn($get) => $get('create_profile')),
                TextInput::make('profile_phone')
                    ->label('Recipient Phone')
                    ->tel()
                    ->required(fn($get) => $get('create_profile')),
            ])->visible(fn($get) => $get('create_profile')),
            Select::make('user_id')
                ->label('User (if exists)')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->nullable()
                ->visible(fn($get) => !$get('create_profile')),
            Select::make('vendor_id')
                ->label('Vendor')
                ->relationship('vendor', 'name', fn($query) => $query)
                ->getOptionLabelFromRecordUsing(fn($record) => $record->name . ' (' . $record->id . ', ' . $record->phone . ')')
                ->searchable()
                ->preload(),
            Select::make('type')
                ->options([
                    'product' => 'Product',
                    'offer' => 'Offer',
                ])
                ->label('Gift Card Type')
                ->required()
                ->reactive(),
            Select::make('product_type')
                ->options([
                    'product' => 'Product',
                    'service' => 'Service',
                ])
                ->label('Product/Service Type')
                ->required()
                ->default('product')
                ->reactive()
                ->visible(fn($get) => $get('type') === 'product'),
            Select::make('product_id')
                ->label('Product/Service')
                ->relationship('product', 'title', function ($query, $get) {
                    if ($get('product_type') === 'service') {
                        $query->where('type', 'service');
                    } elseif ($get('product_type') === 'product') {
                        $query->where('type', 'product');
                    }
                })
                ->getOptionLabelFromRecordUsing(fn($record) => $record->title . ' (' . $record->id . ')')
                ->searchable()
                ->preload()
                ->visible(fn($get) => $get('type') === 'product' && $get('product_type')),
            Select::make('offer_id')
                ->label('Offer')
                ->relationship('offer', 'title', fn($query) => $query->whereNotNull('title')->where('title', '!=', ''))
                ->getOptionLabelFromRecordUsing(fn($record) => $record->title . ' (' . $record->id . ')')
                ->searchable()
                ->preload()
                ->visible(fn($get) => $get('type') === 'offer'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('code')->label('Gift Card Code')->searchable(),
            Tables\Columns\TextColumn::make('amount')->label('Amount'),
            Tables\Columns\TextColumn::make('currency')->label('Currency'),
            Tables\Columns\BadgeColumn::make('status')->label('Status')
                ->colors([
                    'success' => 'active',
                    'danger' => 'expired',
                    'warning' => 'used',
                ]),
            Tables\Columns\TextColumn::make('display_name')
                ->label('Recipient Name')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('display_email')
                ->label('Recipient Email')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('display_phone')
                ->label('Recipient Number')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('vendor_id')->label('Vendor ID'),
            Tables\Columns\TextColumn::make('created_at')->label('Created At')->dateTime()->sortable(),
            Tables\Columns\TextColumn::make('product_type')
                ->label('Product/Service Type')
                ->sortable(),
        ])->defaultSort('id', 'desc')
        ->filters([
            Tables\Filters\SelectFilter::make('type')
                ->options([
                    'product' => 'Product',
                    'offer' => 'Offer',
                ]),
            Tables\Filters\SelectFilter::make('vendor_id')
                ->relationship('vendor', 'name'),
            Tables\Filters\Filter::make('amount_range')
                ->form([
                    Forms\Components\TextInput::make('amount_from')->numeric()->label('Amount From'),
                    Forms\Components\TextInput::make('amount_to')->numeric()->label('Amount To'),
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
        ]);
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
