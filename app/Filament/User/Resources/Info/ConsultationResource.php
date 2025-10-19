<?php

namespace App\Filament\User\Resources\Info;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Consultation;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use App\Traits\FilamentVendorAccess;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\User\Resources\Info\ConsultationResource\Pages;

class ConsultationResource extends Resource
{
    use FilamentVendorAccess;

    protected static ?string $model = Consultation::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Info';

    protected static ?string $navigationLabel = 'consultations';

    protected static ?Consultation $record = null;

    public static function canViewAny(): bool
    {
        return (bool) auth()->user()?->Vendor?->isParent();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Consultation Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Client'),
                        Forms\Components\Select::make('status')
                            ->options([
                                Consultation::STATUS_PENDING => 'Pending',
                                Consultation::STATUS_SCHEDULED => 'Scheduled',
                                Consultation::STATUS_CONFIRMED => 'Confirmed',
                                Consultation::STATUS_COMPLETED => 'Completed',
                                Consultation::STATUS_CANCELLED => 'Cancelled',
                                Consultation::STATUS_REJECTED => 'Rejected',
                            ])
                            ->required()
                            ->label('Status'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Schedule')
                    ->schema([
                        Forms\Components\DatePicker::make('appointment_date')
                            ->required()
                            ->label('Consultation Date'),
                        Forms\Components\TimePicker::make('from_time')
                            ->required()
                            ->label('Start Time'),
                        Forms\Components\TimePicker::make('to_time')
                            ->required()
                            ->after('from_time')
                            ->label('End Time'),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Consultation Details')
                    ->schema([
                        Forms\Components\TextInput::make('consultation_fee')
                            ->numeric()
                            ->prefix('$')
                            ->label('Consultation Fee'),
                        Forms\Components\Toggle::make('is_online')
                            ->label('Online Consultation')
                            ->helperText('Enable this if consultation will be conducted online'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Consultation Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Client')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('appointment_date')
                    ->date('M d, Y')
                    ->sortable()
                    ->label('Date'),
                Tables\Columns\TextColumn::make('from_time')
                    ->time('h:i A')
                    ->label('Start'),
                Tables\Columns\TextColumn::make('to_time')
                    ->time('h:i A')
                    ->label('End'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'primary' => Consultation::STATUS_PENDING,
                        'warning' => Consultation::STATUS_SCHEDULED,
                        'success' => [Consultation::STATUS_CONFIRMED, Consultation::STATUS_COMPLETED],
                        'danger' => [Consultation::STATUS_CANCELLED, Consultation::STATUS_REJECTED],
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state)))
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('is_online')
                    ->label('Online')
                    ->trueIcon('heroicon-o-video-camera')
                    ->falseIcon('heroicon-o-building-office-2')
                    ->sortable(),
                Tables\Columns\TextColumn::make('consultation_fee')
                    ->money('USD')
                    ->label('Fee')
                    ->sortable(),
                Tables\Columns\TextColumn::make('notes')
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y • h:i A')
                    ->label('Created')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        Consultation::STATUS_PENDING => 'Pending',
                        Consultation::STATUS_SCHEDULED => 'Scheduled',
                        Consultation::STATUS_CONFIRMED => 'Confirmed',
                        Consultation::STATUS_COMPLETED => 'Completed',
                        Consultation::STATUS_CANCELLED => 'Cancelled',
                        Consultation::STATUS_REJECTED => 'Rejected',
                    ])
                    ->label('Status'),
                SelectFilter::make('is_online')
                    ->options([
                        '1' => 'Online',
                        '0' => 'In-Person',
                    ])
                    ->label('Consultation Type'),
                Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('date_from'),
                        Forms\Components\DatePicker::make('date_to')->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('appointment_date', '>=', $date),
                            )
                            ->when(
                                $data['date_to'],
                                fn (Builder $query, $date): Builder => $query->whereDate('appointment_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        
                        if ($data['date_from'] ?? null) {
                            $indicators['date_from'] = 'From ' . \Carbon\Carbon::parse($data['date_from'])->toFormattedDateString();
                        }
                        
                        if ($data['date_to'] ?? null) {
                            $indicators['date_to'] = 'Until ' . \Carbon\Carbon::parse($data['date_to'])->toFormattedDateString();
                        }
                        
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('update_status')
                        ->label('Update Status')
                        ->icon('heroicon-o-arrow-path')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->options([
                                    Consultation::STATUS_PENDING => 'Pending',
                                    Consultation::STATUS_SCHEDULED => 'Scheduled',
                                    Consultation::STATUS_CONFIRMED => 'Confirmed',
                                    Consultation::STATUS_COMPLETED => 'Completed',
                                    Consultation::STATUS_CANCELLED => 'Cancelled',
                                    Consultation::STATUS_REJECTED => 'Rejected',
                                ])
                                ->required()
                                ->label('Status'),
                            Forms\Components\Textarea::make('notes')
                                ->label('Status Update Notes')
                                ->rows(3),
                        ])
                        ->action(function (Consultation $record, array $data) {
                            $oldStatus = $record->status;
                            $record->update([
                                'status' => $data['status'],
                                'notes' => $data['notes'] ? ($record->notes ? $record->notes . "\n\n" . $data['notes'] : $data['notes']) : $record->notes,
                            ]);
                            
                            Notification::make()
                                ->title("Consultation status updated from " . ucfirst(str_replace('_', ' ', $oldStatus)) . " to " . ucfirst(str_replace('_', ' ', $data['status'])))
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('reschedule')
                        ->label('Reschedule')
                        ->icon('heroicon-o-calendar')
                        ->form([
                            Forms\Components\DatePicker::make('date')
                                ->required()
                                ->label('New Date'),
                            Forms\Components\TimePicker::make('from_time')
                                ->required()
                                ->label('Start Time'),
                            Forms\Components\TimePicker::make('to_time')
                                ->required()
                                ->after('from_time')
                                ->label('End Time'),
                            Forms\Components\Textarea::make('reschedule_notes')
                                ->label('Reschedule Reason')
                                ->rows(3),
                        ])
                        ->action(function (Consultation $record, array $data) {
                            $record->update([
                                'date' => $data['date'],
                                'from_time' => $data['from_time'],
                                'to_time' => $data['to_time'],
                                'status' => Consultation::STATUS_SCHEDULED,
                                'notes' => $data['reschedule_notes'] ? ($record->notes ? $record->notes . "\n\n" . "Rescheduled: " . $data['reschedule_notes'] : "Rescheduled: " . $data['reschedule_notes']) : $record->notes,
                            ]);
                        
                            Notification::make()
                                ->title('Consultation rescheduled successfully')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('update_status_bulk')
                        ->label('Update Status')
                        ->icon('heroicon-o-arrow-path')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->options([
                                    Consultation::STATUS_PENDING => 'Pending',
                                    Consultation::STATUS_SCHEDULED => 'Scheduled',
                                    Consultation::STATUS_CONFIRMED => 'Confirmed',
                                    Consultation::STATUS_COMPLETED => 'Completed',
                                    Consultation::STATUS_CANCELLED => 'Cancelled',
                                    Consultation::STATUS_REJECTED => 'Rejected',
                                ])
                                ->required()
                                ->label('Status'),
                        ])
                        ->action(function ($records, array $data) {
                            $records->each->update(['status' => $data['status']]);
                            
                            Notification::make()
                                ->title('Status updated for selected consultations')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('appointment_date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // Add relation managers if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConsultations::route('/'),
            'create' => Pages\CreateConsultation::route('/create'),
            'edit' => Pages\EditConsultation::route('/{record}/edit'),
            'view' => Pages\ViewConsultation::route('/{record}'),
        ];
    }
}