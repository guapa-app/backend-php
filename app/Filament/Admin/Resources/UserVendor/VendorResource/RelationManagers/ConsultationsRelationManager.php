<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Consultation;
use Filament\Tables\Filters\Filter;
use Filament\Notifications\Notification;
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
                        ->options([
                            'in_person' => 'In Person',
                            'audio' => 'Audio',
                            'video' => 'Video'
                        ])
                        ->required(),
                ]),

            Forms\Components\Section::make('Medical Information')
                ->schema([
                    Forms\Components\Textarea::make('chief_complaint')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Enter the primary reason for the consultation...')
                        ->columnSpanFull(),
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
                                ->visible(fn($get) => $get('type') === 'choice'),
                        ])
                        ->columns(2)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Payment')
                ->schema([
                    Forms\Components\TextInput::make('consultation_fee')
                        ->numeric()
                        ->minValue(0)
                        ->prefix('$')
                        ->label('Consultation Fee')
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set, $get) {
                            $fee = floatval($state);
                            $tax = floatval($get('tax_amount') ?? 0);
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
                            $fee = floatval($get('consultation_fee') ?? 0);
                            $set('total_amount', $fee + $tax);
                        }),
                    Forms\Components\TextInput::make('total_amount')
                        ->numeric()
                        ->prefix('$')
                        ->disabled(),
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
                        ->disabled(fn ($record) => $record && ($record->status === Consultation::STATUS_CANCELLED || $record->status === Consultation::STATUS_REJECTED))
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
                    ->label('Patient')
                    ->formatStateUsing(fn ($state) => ucwords($state)),

                Tables\Columns\TextColumn::make('total_amount')
                    ->money()
                    ->sortable()
                    ->label('Amount'),

                Tables\Columns\IconColumn::make('has_images')
                    ->boolean()
                    ->label('Images')
                    ->getStateUsing(fn (Consultation $record): bool => $record->media->count() > 0),

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
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                    
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

                Filter::make('has_images')
                    ->label('Has Images')
                    ->query(function ($query) {
                        return $query->whereHas('media');
                    }),

                Filter::make('upcoming')
                    ->label('Upcoming Consultations')
                    ->query(fn(Builder $query): Builder => $query->where('appointment_date', '>=', Carbon::today()))
                    ->default(),

                Filter::make('past')
                    ->label('Past Consultations')
                    ->query(fn(Builder $query): Builder => $query->where('appointment_date', '<', Carbon::today())),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->form($this->getFormSchema())
                        ->modalWidth('4xl'),

                    Tables\Actions\EditAction::make()
                        ->form($this->getFormSchema())
                        ->modalWidth('4xl')
                        ->mutateFormDataUsing(function (array $data): array {
                            // Preserve any calculated fields or additional processing here
                            return $data;
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Consultation updated')
                                ->body('The consultation has been updated successfully.')
                        ),

                    Tables\Actions\DeleteAction::make(),

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
}