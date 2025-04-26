<?php

namespace App\Filament\Admin\Resources\UserVendor;

use Filament\Forms;
use Filament\Tables;
use App\Models\Vendor;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Consultation;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Admin\Resources\UserVendor\ConsultationResource\Pages;
use App\Filament\Admin\Resources\UserVendor\ConsultationResource\RelationManagers;

class ConsultationResource extends Resource
{
    protected static ?string $model = Consultation::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'User & Vendor';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Consultation Details')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->native(false)
                            ->relationship('user', 'name')
                            ->preload()
                            ->searchable()
                            ->required()
                            ->label('Patient')
                            ->validationMessages([
                                'required' => 'Please select a patient.',
                            ]),
                        Forms\Components\Select::make('vendor_id')
                            ->native(false)
                            ->relationship('vendor', 'name')
                            ->preload()
                            ->searchable()
                            ->label('Consultant')
                            ->required()
                            ->validationMessages([
                                'required' => 'Please select a consultant.',
                            ]),
                        Forms\Components\DatePicker::make('appointment_date')
                            ->native(false)
                            ->required()
                            ->label('Appointment Date')
                            ->validationMessages([
                                'required' => 'Please select an appointment date.'
                            ]),
                        Forms\Components\TimePicker::make('appointment_time')
                            ->native(false)
                            ->required()
                            ->seconds(false)
                            ->label('Appointment Time')
                            ->validationMessages([
                                'required' => 'Please select an appointment time.',
                            ]),
                        Forms\Components\Select::make('type')
                            ->options([
                                'video' => 'Video',
                                'in-person' => 'In-Person',
                                'Audio' => 'Audio',
                            ])
                            ->native(false)
                            ->required()
                            ->label('Consultation Type')
                            ->validationMessages([
                                'required' => 'Please select a consultation type.',
                            ]),

                        Forms\Components\Select::make('status')
                            ->options([
                                Consultation::STATUS_PENDING => 'Pending',
                                Consultation::STATUS_SCHEDULED => 'Scheduled',
                                Consultation::STATUS_CONFIRMED => 'Confirmed',
                                Consultation::STATUS_COMPLETED => 'Completed',
                                Consultation::STATUS_CANCELLED => 'Cancelled',
                                Consultation::STATUS_REJECTED => 'Rejected',
                            ])
                            ->default(Consultation::STATUS_PENDING)
                            ->native(false)
                            ->required()
                            ->disabled(fn ($record) => $record && ($record->status === Consultation::STATUS_CANCELLED || $record->status === Consultation::STATUS_REJECTED))
                            ->label('Status')
                            ->validationMessages([
                                'required' => 'Please select a status.',
                            ]),
                    ])
                    ->columns(2),
                    Forms\Components\Section::make('Medical Information')
                    ->schema([
                        Forms\Components\Textarea::make('chief_complaint')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter the primary reason for the consultation...')
                            ->label('Chief Complaint')
                            ->columnSpanFull()
                            ->validationMessages([
                                'required' => 'Chief complaint is required.',
                                'max' => 'Chief complaint cannot exceed 255 characters.',
                            ]),
                        Forms\Components\Repeater::make('medical_history')
                            ->label('Medical History')
                            ->schema([
                                Forms\Components\TextInput::make('question')
                                    ->label('Question')
                                    ->required(),
                                Forms\Components\Select::make('type')
                                    ->label('Type')
                                    ->options([
                                        'choice' => 'Choice',
                                        'text' => 'Text',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('answer')
                                    ->label('Answer')
                                    ->required(),
                                Forms\Components\TagsInput::make('options')
                                    ->label('Options')
                                    ->placeholder('Enter options for choice type...')
                                    ->visible(fn ($get) => $get('type') === 'choice'),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('consultation_reason')
                            ->maxLength(500)
                            ->placeholder('Enter additional details about the consultation...')
                            ->label('Consultation Reason')
                            ->columnSpanFull()
                            ->validationMessages([
                                'max' => 'Consultation reason cannot exceed 500 characters.',
                            ]),
                    ]),
                Forms\Components\Section::make('Financial Information')
                    ->schema([
                        Forms\Components\TextInput::make('consultation_fee')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('$')
                            ->label('Consultation Fee')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $fee = floatval($state);
                                $tax = floatval($get('tax_amount'));
                                $set('total_amount', $fee + $tax);
                            }),
                        Forms\Components\TextInput::make('tax_amount')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('$')
                            ->label('Tax Amount')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $tax = floatval($state);
                                $fee = floatval($get('consultation_fee'));
                                $set('total_amount', $fee + $tax);
                            }),
                        Forms\Components\TextInput::make('total_amount')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('$')
                            ->label('Total Amount')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ])
                            ->native(false)
                            ->label('Payment Status'),
                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'credit_card' => 'Credit Card',
                                'wallet' => 'Wallet',
                            ])
                            ->native(false)
                            ->label('Payment Method'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable()
                    ->label('Client')
                    ->formatStateUsing(fn ($state) => ucwords($state)),
                Tables\Columns\TextColumn::make('vendor.name')
                    ->sortable()
                    ->searchable()
                    ->label('Consultant')
                    ->formatStateUsing(fn ($state) => ucwords($state)),
                Tables\Columns\TextColumn::make('type')
                    ->sortable()
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('chief_complaint')
                    ->limit(30)
                    ->searchable()
                    ->label('Complaint'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->color(fn (string $state): string => match ($state) {
                        Consultation::STATUS_PENDING => 'warning',
                        Consultation::STATUS_SCHEDULED => 'info',
                        Consultation::STATUS_CONFIRMED => 'success',
                        Consultation::STATUS_COMPLETED => 'success',
                        Consultation::STATUS_CANCELLED => 'danger',
                        Consultation::STATUS_REJECTED => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('appointment_date')
                    ->date()
                    ->sortable()
                    ->label('Appointment'),
                Tables\Columns\TextColumn::make('appointment_time')
                    ->time()
                    ->sortable()
                    ->label('Time'),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('USD')
                    ->sortable()
                    ->label('Total'),
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
                SelectFilter::make('type')
                    ->options([
                        'general' => 'General',
                        'specialist' => 'Specialist',
                        'follow-up' => 'Follow-Up',
                    ])
                    ->label('Type'),
                Tables\Filters\Filter::make('appointment_date')
                    ->form([
                        Forms\Components\DatePicker::make('appointment_from')
                            ->label('From'),
                        Forms\Components\DatePicker::make('appointment_to')
                            ->label('To'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['appointment_from'], fn ($q) => $q->whereDate('appointment_date', '>=', $data['appointment_from']))
                            ->when($data['appointment_to'], fn ($q) => $q->whereDate('appointment_date', '<=', $data['appointment_to']));
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('confirm')
                        ->label('Confirm')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Consultation $record) {
                            $record->update(['status' => Consultation::STATUS_CONFIRMED]);
                            Notification::make()
                                ->title('Consultation Confirmed')
                                ->success()
                                ->send();
                        })
                        ->visible(fn (Consultation $record) => in_array($record->status, [Consultation::STATUS_PENDING, Consultation::STATUS_SCHEDULED])),
                    Tables\Actions\Action::make('cancel')
                        ->label('Cancel')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Consultation $record) {
                            $record->update(['status' => Consultation::STATUS_CANCELLED]);
                            Notification::make()
                                ->title('Consultation Cancelled')
                                ->danger()
                                ->send();
                        })
                        ->visible(fn (Consultation $record) => !in_array($record->status, [Consultation::STATUS_CANCELLED, Consultation::STATUS_REJECTED, Consultation::STATUS_COMPLETED])),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('confirm_selected')
                        ->label('Confirm Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->update(['status' => Consultation::STATUS_CONFIRMED]);
                            Notification::make()
                                ->title('Selected Consultations Confirmed')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function vendorTable(Table $table): Table
    {
        return $table
            ->query(Vendor::has('consultations'))
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('logo')
                    ->collection('logos')
                    ->label('Logo')
                    ->width(50)
                    ->height(50)
                    ->defaultImageUrl(url('path/to/default-logo.png')),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Doctor'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->label('Email'),
                Tables\Columns\BooleanColumn::make('accept_online_consultation')
                    ->label('Online Consultation')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                Tables\Columns\TextColumn::make('consultations_count')
                    ->label('Total Consultations')
                    ->counts('consultations')
                    ->sortable(),
                Tables\Columns\TextColumn::make('consultation_statuses')
                    ->label('Consultation Statuses')
                    ->getStateUsing(function (Vendor $record) {
                        $statuses = $record->consultations()
                            ->groupBy('status')
                            ->selectRaw('status, COUNT(*) as count')
                            ->pluck('count', 'status')
                            ->mapWithKeys(function ($count, $status) {
                                return [$status => $count];
                            });
                        return collect([
                            Consultation::STATUS_PENDING => 0,
                            Consultation::STATUS_SCHEDULED => 0,
                            Consultation::STATUS_CONFIRMED => 0,
                            Consultation::STATUS_COMPLETED => 0,
                            Consultation::STATUS_CANCELLED => 0,
                            Consultation::STATUS_REJECTED => 0,
                        ])->merge($statuses)->map(function ($count, $status) {
                            return ucfirst(str_replace('_', ' ', $status)) . ': ' . $count;
                        })->implode(', ');
                    })
                    ->wrap(),
                Tables\Columns\TextColumn::make('about')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state;
                    })
                    ->label('About'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('accept_online_consultation')
                    ->options([
                        0 => 'Disabled',
                        1 => 'Active',
                    ])
                    ->label('Online Consultation'),
            ])
            ->actions([
                Tables\Actions\Action::make('update_details')
                    ->label('Update Details')
                    ->icon('heroicon-o-pencil')
                    ->form([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('logo')
                            ->collection('logos')
                            ->label('Logo')
                            ->image()
                            ->maxSize(2048)
                            ->imageEditor(),
                        Forms\Components\Textarea::make('about')
                            ->label('About')
                            ->maxLength(1000)
                            ->rows(4),
                    ])
                    ->action(function (Vendor $record, array $data) {
                        if (isset($data['about'])) {
                            $record->update(['about' => $data['about']]);
                        }
                        if (isset($data['logo'])) {
                            $record->clearMediaCollection('logos');
                            $record->addMedia($data['logo'])->toMediaCollection('logos');
                        }
                        Notification::make()
                            ->title('Doctor details updated')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make()
                    ->url(fn (Vendor $record): string => VendorResource::getUrl('view', ['record' => $record])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->paginated([10, 25, 50])
            ->defaultSort('name');
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConsultations::route('/'),
            'view' => Pages\ViewConsultation::route('/{record}'),
            'edit' => Pages\EditConsultations::route('/{record}/edit'),
        ];
    }
}