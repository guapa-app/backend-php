<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Consultation;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;

class ConsultationsRelationManager extends RelationManager
{
    protected static string $relationship = 'consultations';

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make('Appointment Details')
                ->schema([
                    Forms\Components\DatePicker::make('appointment_date')
                        ->required(),
                    Forms\Components\TimePicker::make('appointment_time')
                        ->required(),
                    Forms\Components\Select::make('type')
                        ->required(),
                ]),

            Forms\Components\Section::make('Medical Information')
                ->schema([
                    Forms\Components\Textarea::make('chief_complaint')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('medical_history')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Payment')
                ->schema([
                    Forms\Components\TextInput::make('total_amount')
                        ->numeric()
                        ->prefix('$'),
                    Forms\Components\Select::make('payment_status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'failed' => 'Failed',
                        ]),
                    Forms\Components\Select::make('payment_method')
                        ->options([
                            'credit_card' => 'Credit Card',
                            'wallet' => 'Wallet',
                        ])
                ]),

            Forms\Components\Section::make('Status')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            Consultation::STATUS_PENDING => 'Pending',
                            Consultation::STATUS_SCHEDULED => 'Scheduled',
                            Consultation::STATUS_CONFIRMED => 'Confirmed',
                            Consultation::STATUS_COMPLETED => 'Completed',
                            Consultation::STATUS_CANCELLED => 'Cancelled',
                            Consultation::STATUS_REJECTED => 'Rejected',
                        ])
                        ->required(),
                ]),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('consultation_number')
                    ->searchable()
                    ->label('Consultation #'),

                Tables\Columns\TextColumn::make('appointment_date')
                    ->date()
                    ->sortable()
                    ->label('Date'),

                Tables\Columns\TextColumn::make('appointment_time')
                    ->time()
                    ->sortable()
                    ->label('Time'),

                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->label('Patient'),

                Tables\Columns\TextColumn::make('total_amount')
                    ->money()
                    ->sortable()
                    ->label('Amount'),

                BadgeColumn::make('status')
                    ->colors([
                        'primary' => Consultation::STATUS_PENDING,
                        'warning' => Consultation::STATUS_SCHEDULED,
                        'success' => Consultation::STATUS_CONFIRMED,
                        'success' => Consultation::STATUS_COMPLETED,
                        'danger' => Consultation::STATUS_CANCELLED,
                        'danger' => Consultation::STATUS_REJECTED,
                    ])
                    ->label('Status'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type'),

                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                    ]),
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

                SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ])
                    ->label('Payment Status'),

                Filter::make('upcoming')
                    ->label('Upcoming Consultations')
                    ->query(fn(Builder $query): Builder => $query->where('appointment_date', '>=', Carbon::today()))
                    ->default(),

                Filter::make('past')
                    ->label('Past Consultations')
                    ->query(fn(Builder $query): Builder => $query->where('appointment_date', '<', Carbon::today())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->form($this->getFormSchema())
                    ->modalWidth('4xl'),
                
                Tables\Actions\EditAction::make()
                    ->form($this->getFormSchema())
                    ->modalWidth('4xl'),
                    
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}