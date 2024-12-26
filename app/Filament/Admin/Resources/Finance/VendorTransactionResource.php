<?php

namespace App\Filament\Admin\Resources\Finance;

use App\Enums\TransactionOperation;
use App\Enums\TransactionType;
use App\Filament\Admin\Resources\Finance\VendorTransactionResource\Pages;
use App\Filament\Widgets\VendorTransactionStatsWidget;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;

class VendorTransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Finance';

    public static function getLabel(): string
    {
        return 'Vendor Transaction';
    }

    public static function getPluralLabel(): string
    {
        return 'Vendor Transactions';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereNotNull('vendor_id'); // Only show vendor transactions
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('vendor_id')
                    ->relationship('vendor', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('transaction_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\Select::make('operation')
                    ->options([TransactionOperation::WITHDRAWAL->value => 'Withdrawal (from vendor wallet)'])

                    ->required(),
                Forms\Components\Select::make('transaction_type')
                    ->options([
                        TransactionType::VENDOR_PAYOUT->value => 'Vendor Payout',
//                        TransactionType::ORDER_PAYMENT->value => 'Order Payment',
                    ])
                    ->required(),
                Forms\Components\DateTimePicker::make('transaction_date')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vendor.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('operation')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Deposit' => 'success',
                        'Withdrawal' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('transaction_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        'cancelled' => 'danger',
                    }),
            ])
            ->filters([
                SelectFilter::make('vendor')
                    ->relationship('vendor', 'name'),
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('operation')
                    ->options(TransactionType::class),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            VendorTransactionStatsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVendorTransactions::route('/'),
            'create' => Pages\CreateVendorTransaction::route('/create'),
        ];
    }
}
