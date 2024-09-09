<?php

namespace App\Filament\Resources\Shop;

use App\Enums\OrderStatus;
use App\Filament\Resources\Shop\OrderResource\Pages;
use App\Filament\Resources\Shop\OrderResource\RelationManagers;
use App\Filament\Resources\Shop\OrderResource\Widgets\OrderStats;
use App\Models\Order;
use App\Notifications\PushNotification;
use App\Traits\FilamentVendorAccess;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class OrderResource extends Resource
{
    use FilamentVendorAccess;

    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema(static::getFormSchema())
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => fn (?Order $record) => $record === null ? 3 : 2]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('paid_amount')
                            ->label('Paid Amount')
                            ->content(fn (Order $record): ?string => $record->paid_amount),

                        Forms\Components\Placeholder::make('paid_amount_with_taxes')
                            ->label('Paid Amount with Taxes')
                            ->content(fn (Order $record): ?string => $record->paid_amount_with_taxes),
                        Forms\Components\Placeholder::make('remaining_amount')
                            ->label('Remaining Amount')
                            ->content(fn (Order $record): ?string => $record->remaining_amount),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Order $record) => $record === null),

                   Forms\Components\Section::make('Order Items')
                        ->schema([
                        Forms\Components\Placeholder::make('')
                            ->content(fn ($record) =>  view('filament.resources.shop.orders.items', [
                                'items' => $record->items,
                            ])),
                    ])
                ->columnSpan(['lg' => 3])
                ->hidden(fn (?Order $record) => $record === null),
                        ])
            ->columns(3);
    }

    public static function getFormSchema(string $section = null): array
    {
        return [
            Forms\Components\Placeholder::make('id')
                ->label('Order ID')
                ->content(fn (Order $record): ?string => $record->id),

            Forms\Components\Placeholder::make('status')
                ->label('Status')
                ->content(fn (Order $record): ?string => $record->status->value),
            Forms\Components\Placeholder::make('total')
                ->label('Total')
                ->content(fn (Order $record): ?string => $record->total),

            Forms\Components\Placeholder::make('name')
                ->label('Customer Name')
                ->content(fn (Order $record): ?string => $record->name),

            Forms\Components\Placeholder::make('phone')
                ->label('Phone')
                ->content(fn (Order $record): ?string => $record->phone),

            Forms\Components\Placeholder::make('note')
                ->label('Note')
                ->content(fn (Order $record): ?string => $record->note),

            Forms\Components\Placeholder::make('created_at')
                ->label('Created at')
                ->content(fn (Order $record): ?string => $record->created_at?->diffForHumans()),

            Forms\Components\Placeholder::make('updated_at')
                ->label('Last modified at')
                ->content(fn (Order $record): ?string => $record->updated_at?->diffForHumans()),

        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
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
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()->color('success'),
                Tables\Actions\Action::make('sendNotification')
                    ->label('Send Notification')
                    ->action(fn ($record) => static::sendNotification($record))
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === OrderStatus::Pending)
                    ->icon('heroicon-o-bell')
                    ->color('warning'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrderItemsRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
            'view'   => Pages\ViewOrder::route('/{record}'),
        ];
    }

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function sendNotification($order)
    {
        $message = 'تذكير: لديك طلب غير مكتمل. يرجى إكمال البيانات وإتمام الدفع لتأكيد طلبك.';

        // Sending the notification
        $order->user->notify(new PushNotification(
            'تذكير بالطلب غير المكتمل',
            $message,
            ''
        ));

        FilamentNotification::make()
            ->title('Notification sent successfully.')
            ->success()
            ->send();
    }
}
