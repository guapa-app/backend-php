<?php

namespace App\Filament\Admin\Resources\UserVendor\ConsultationResource\Widgets;

use Filament\Forms;
use Filament\Tables;
use App\Models\Vendor;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

class VendorsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = -1; // Make sure the widget appears at the top
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Vendor::query()
                    ->withCount('consultations')
            )
            ->heading('Vendors Overview')
            ->description('Manage vendors and their consultation status')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\IconColumn::make('accept_online_consultation')
                    ->boolean()
                    ->sortable()
                    ->label('Accepts Online Consultations'),
                Tables\Columns\TextColumn::make('consultations_count')
                    ->counts('consultations')
                    ->sortable()
                    ->label('Total Consultations'),
                Tables\Columns\TextColumn::make('type')
                    ->sortable()
                    ->badge(),
                Tables\Columns\IconColumn::make('verified')
                    ->boolean()
                    ->sortable()
                    ->label('Verified'),
                Tables\Columns\IconColumn::make('status')
                    ->boolean()
                    ->sortable()
                    ->label('Active'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(Vendor::TYPES)
                    ->label('Vendor Type'),
                Tables\Filters\Filter::make('accepts_consultations')
                    ->query(fn (Builder $query): Builder => $query->where('accept_online_consultation', 1))
                    ->label('Accepts Online Consultations'),
                Tables\Filters\Filter::make('with_consultations')
                    ->query(fn (Builder $query): Builder => $query->has('consultations'))
                    ->label('Has Consultations'),
                Tables\Filters\Filter::make('status')
                    ->query(fn (Builder $query): Builder => $query->where('status', 1))
                    ->label('Active Only'),
            ])
            ->actions([
                Tables\Actions\Action::make('toggle_consultation')
                    ->label(fn (Vendor $record): string => $record->accept_online_consultation ? 'Disable Consultations' : 'Enable Consultations')
                    ->icon(fn (Vendor $record): string => $record->accept_online_consultation ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (Vendor $record): string => $record->accept_online_consultation ? 'danger' : 'success')
                    ->requiresConfirmation()
                    ->action(function (Vendor $record): void {
                        $record->update([
                            'accept_online_consultation' => !$record->accept_online_consultation,
                        ]);
                        $status = $record->accept_online_consultation ? 'enabled' : 'disabled';
                        Notification::make()
                            ->title("Online consultations $status for {$record->name}")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('edit')
                    ->label('Edit Vendor')
                    ->icon('heroicon-o-pencil')
                    ->color('primary')
                    ->mountUsing(fn (Forms\Form $form, Vendor $record) => $form->fill($record->toArray()))
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(150),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->options(Vendor::TYPES)
                            ->required()
                            ->native(false),
                        Forms\Components\Toggle::make('accept_online_consultation')
                            ->label('Accept Online Consultations')
                            ->onIcon('heroicon-o-check')
                            ->offIcon('heroicon-o-x-mark')
                            ->required(),
                        Forms\Components\Toggle::make('status')
                            ->label('Active')
                            ->onIcon('heroicon-o-check')
                            ->offIcon('heroicon-o-x-mark')
                            ->required(),
                        Forms\Components\Toggle::make('verified')
                            ->label('Verified')
                            ->onIcon('heroicon-o-check')
                            ->offIcon('heroicon-o-x-mark')
                            ->required(),
                        Forms\Components\Textarea::make('about')
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ])
                    ->action(function (Vendor $record, array $data): void {
                        $record->update($data);
                        Notification::make()
                            ->title("Vendor {$record->name} updated successfully")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('view_consultations')
                    ->label('View Consultations')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Vendor $record): string => route('filament.admin.resources.user-vendor.consultations.index', [
                        'tableFilters[vendor_id][value]' => $record->id,
                    ]))
                    ->visible(fn (Vendor $record): bool => $record->consultations_count > 0),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('enable_consultations')
                    ->label('Enable Consultations')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($records): void {
                        $records->each(function ($record) {
                            $record->update(['accept_online_consultation' => 1]);
                        });
                        Notification::make()
                            ->title('Online consultations enabled for selected vendors')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
                Tables\Actions\BulkAction::make('disable_consultations')
                    ->label('Disable Consultations')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($records): void {
                        $records->each(function ($record) {
                            $record->update(['accept_online_consultation' => 0]);
                        });
                        Notification::make()
                            ->title('Online consultations disabled for selected vendors')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
            ]);
    }
}