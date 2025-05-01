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
                                'audio' => 'Audio',
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
                        Forms\Components\SpatieMediaLibraryFileUpload::make('media')
                            ->collection('consultations')
                            ->multiple()
                            ->maxFiles(10)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                            ->maxSize(5120) // 5MB max file size
                            ->columnSpanFull()
                            ->label('Medical Images')
                            ->helperText('Upload any relevant medical images (max 5MB each)'),
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
                Tables\Columns\IconColumn::make('has_images')
                    ->boolean()
                    ->label('Images')
                    ->getStateUsing(fn (Consultation $record): bool => $record->media->count() > 0),
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
                        'video' => 'Video',
                        'in-person' => 'In-Person',
                        'audio' => 'Audio',
                    ])
                    ->label('Type'),
                Tables\Filters\Filter::make('has_images')
                    ->label('Has Images')
                    ->query(function ($query) {
                        return $query->whereHas('media');
                    }),
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
                    Tables\Actions\Action::make('upload_images')
                        ->label('Upload Images')
                        ->icon('heroicon-o-photo')
                        ->form([
                            Forms\Components\SpatieMediaLibraryFileUpload::make('media')
                                ->collection('consultations')
                                ->multiple()
                                ->maxFiles(10)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                                ->maxSize(5120) // 5MB max file size
                                ->label('Medical Images'),
                        ])
                        ->action(function (Consultation $record, array $data) {
                            Notification::make()
                                ->title('Images uploaded successfully')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('view_images')
                        ->label('View Images')
                        ->icon('heroicon-o-photo')
                        ->modalHeading(fn (Consultation $record) => 'Images for consultation #' . $record->id)
                        ->modalContent(function (Consultation $record) {
                            $media = $record->media;
                            
                            $content = '<div class="space-y-4">';
                            
                            if ($media->count() > 0) {
                                $content .= '<div><h3 class="text-lg font-bold">Medical Images</h3><div class="grid grid-cols-2 gap-4 mt-2">';
                                foreach ($media as $item) {
                                    // Generate appropriate URLs based on storage type
                                    $imageUrl = $item->disk === 's3' ? $item->getTemporaryUrl(now()->addMinutes(20)) : $item->getFullUrl();
                                    
                                    // Try to get thumbnail if available, fallback to original
                                    $thumbUrl = null;
                                    if ($item->hasGeneratedConversion('small')) {
                                        $thumbUrl = $item->conversions_disk === 's3' 
                                            ? $item->getTemporaryUrl(now()->addMinutes(20), 'small') 
                                            : $item->getFullUrl('small');
                                    } else {
                                        $thumbUrl = $imageUrl;
                                    }
                                    
                                    $content .= '<div class="p-2 border rounded">
                                        <div class="flex flex-col items-center">
                                            <img src="' . htmlspecialchars($thumbUrl) . '" alt="' . htmlspecialchars($item->file_name) . '" class="object-cover h-48 w-full" />
                                            <span class="text-gray-700 mt-2">' . htmlspecialchars($item->file_name) . '</span>
                                            <a href="' . htmlspecialchars($imageUrl) . '" target="_blank" class="mt-1 text-primary-500 hover:underline">View Full Size</a>
                                        </div>
                                    </div>';
                                }
                                $content .= '</div></div>';
                            } else {
                                $content .= '<div class="text-center py-4">No images available for this consultation.</div>';
                            }
                            
                            $content .= '</div>';
                            
                            return new \Illuminate\Support\HtmlString($content);
                        })
                        ->modalSubmitAction(false)
                        ->modalCancelAction(false)
                        ->visible(fn (Consultation $record): bool => $record->media->count() > 0),
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

    public static function getRelations(): array
    {
        return [
            // You can add relation managers here if needed
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