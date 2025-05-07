<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Review;
use Filament\Tables\Table;
use Flowframe\Trend\Trend;
use App\Models\Consultation;
use Flowframe\Trend\TrendValue;
use Filament\Forms\Components\Tabs;
use Filament\Tables\Filters\Filter;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Resources\RelationManagers\RelationManager;

class ConsultationsRelationManager extends RelationManager
{
    protected static string $relationship = 'consultations';

    protected function getFormSchema(): array
    {
        return [
            Tabs::make('Consultation Management')
                ->tabs([
                    Tabs\Tab::make('Consultation Details')
                        ->schema([
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
                                        ->maxSize(5120)
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
                                        ->required(),
                                ]),
                        ]),

                    Tabs\Tab::make('Reviews')
                        ->schema([
                            Forms\Components\Section::make('Patient Reviews')
                                ->description('Manage reviews for this consultation')
                                ->schema([
                                    Forms\Components\Placeholder::make('review_info')
                                        ->content(function ($record, $livewire) {
                                            $review = $record ? Review::where('reviewable_id', $record->id)
                                                ->where('reviewable_type', Consultation::class)
                                                ->first() : null;

                                            if ($review) {
                                                return new \Illuminate\Support\HtmlString(
                                                    "<div class='flex flex-col space-y-4'>
                                                        <div class='flex flex-col space-y-2'>
                                                            <div>Review by {$review->user->name}</div>
                                                            <div class='flex items-center'>
                                                                <div class='flex text-yellow-400'>
                                                                    " . $this->generateStarRating($review->stars) . "
                                                                </div>
                                                                <span class='ml-2'>({$review->stars}/5)</span>
                                                            </div>
                                                            <div>" . Carbon::parse($review->created_at)->format('M d, Y') . "</div>
                                                        </div>
                                                        <div class='mt-2 p-4 bg-gray-50 rounded-lg'>
                                                            <h4 class='font-medium mb-1'>Comment:</h4>
                                                            <p class='text-gray-700'>" . nl2br(htmlspecialchars($review->comment)) . "</p>
                                                        </div>
                                                        " . ($livewire instanceof \Filament\Actions\ViewAction ? '' : "
                                                        <div class='mt-4'>
                                                            <button type='button' class='px-4 py-2 bg-primary-500 text-white rounded hover:bg-primary-600 disabled:opacity-50 disabled:cursor-not-allowed' 
                                                                onclick='document.getElementById(\"edit-review-form\").classList.toggle(\"hidden\")'>
                                                                Edit Review
                                                            </button>
                                                        </div>
                                                        ") . "
                                                    </div>"
                                                );
                                            }

                                            return new \Illuminate\Support\HtmlString(
                                                'No reviews for this consultation yet.' . 
                                                ($livewire instanceof \Filament\Actions\ViewAction ? '' : '
                                                <div class="mt-4">
                                                    <button type="button" class="px-4 py-2 bg-primary-500 text-white rounded hover:bg-primary-600" 
                                                        onclick="document.getElementById(\'edit-review-form\').classList.toggle(\'hidden\')">
                                                        Add Review
                                                    </button>
                                                </div>')
                                            );
                                        })
                                        ->columnSpanFull(),

                                    Forms\Components\Hidden::make('review_id')
                                        ->default(function ($record) {
                                            if (!$record) {
                                                return null;
                                            }
                                            $review = Review::where('reviewable_id', $record->id)
                                                ->where('reviewable_type', Consultation::class)
                                                ->first();
                                            return $review?->id;
                                        }),

                                    Forms\Components\Grid::make(['default' => 1])
                                        ->schema([
                                            Forms\Components\TextInput::make('stars')
                                                ->label('Rating')
                                                ->numeric()
                                                ->minValue(1)
                                                ->maxValue(5)
                                                ->step(0.1)
                                                ->default(function ($livewire) {
                                                    $record = $livewire->record ?? $livewire->getRecord();
                                                    if (!$record) {
                                                        return 0;
                                                    }
                                                    $review = Review::where('reviewable_id', $record->id)
                                                        ->where('reviewable_type', Consultation::class)
                                                        ->first();
                                                    return $review?->stars ?? 0;
                                                })
                                                ->required()
                                                ->helperText('Enter a rating between 1 and 5 (e.g., 4.7)')
                                                ->disabled(fn($livewire) => $livewire instanceof \Filament\Actions\ViewAction),

                                            Forms\Components\Textarea::make('comment')
                                                ->label('Review Comment')
                                                ->placeholder('Patient feedback about the consultation...')
                                                ->default(function ($livewire) {
                                                    $record = $livewire->record ?? $livewire->getRecord();
                                                    if (!$record) {
                                                        return '';
                                                    }
                                                    $review = Review::where('reviewable_id', $record->id)
                                                        ->where('reviewable_type', Consultation::class)
                                                        ->first();
                                                    return $review?->comment ?? '';
                                                })
                                                ->helperText('Enter any comments or feedback from the patient')
                                                ->columnSpanFull()
                                                ->disabled(fn($livewire) => $livewire instanceof \Filament\Actions\ViewAction),
                                        ])
                                        ->id('edit-review-form')
                                        ->extraAttributes(['class' => 'hidden']),
                                ]),
                        ]),
                ])
                ->columnSpanFull(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
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
                    ->formatStateUsing(fn($state) => ucwords($state)),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money()
                    ->sortable()
                    ->label('Amount'),
                Tables\Columns\IconColumn::make('has_images')
                    ->boolean()
                    ->label('Images')
                    ->getStateUsing(fn(Consultation $record): bool => $record->media->count() > 0),
                Tables\Columns\IconColumn::make('has_review')
                    ->boolean()
                    ->label('Reviewed')
                    ->getStateUsing(fn(Consultation $record): bool => Review::where('reviewable_id', $record->id)
                        ->where('reviewable_type', Consultation::class)
                        ->exists()),
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
                    ->formatStateUsing(fn($state) => ucfirst($state)),
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
                    ->query(fn(Builder $query): Builder => $query->whereHas('media')),
                Filter::make('has_review')
                    ->label('Has Review')
                    ->query(fn(Builder $query): Builder => $query->whereHas('reviews')),
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
                        ->mutateFormDataUsing(function (array $data, Consultation $record): array {
                            $reviewData = [
                                'stars' => $data['stars'] ?? null,
                                'comment' => $data['comment'] ?? null,
                            ];

                            unset($data['stars'], $data['comment'], $data['review_id']);

                            if (!empty($reviewData['stars'])) {
                                $review = Review::firstOrNew([
                                    'reviewable_id' => $record->id,
                                    'reviewable_type' => get_class($record),
                                ]);
                                $review->user_id = $record->user_id;
                                $review->stars = $reviewData['stars'];
                                $review->comment = $reviewData['comment'];
                                $review->save();
                            }

                            return $data;
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Consultation updated')
                                ->body('The consultation has been updated successfully.')
                        ),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('mark_completed')
                        ->label('Mark Completed')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Consultation $record) {
                            $record->update(['status' => Consultation::STATUS_COMPLETED]);
                            Notification::make()
                                ->title('Consultation Marked as Completed')
                                ->success()
                                ->send();
                        })
                        ->visible(fn(Consultation $record) => !in_array($record->status, [Consultation::STATUS_COMPLETED, Consultation::STATUS_CANCELLED, Consultation::STATUS_REJECTED])),
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
                        ->visible(fn(Consultation $record) => in_array($record->status, [Consultation::STATUS_PENDING, Consultation::STATUS_SCHEDULED])),
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
                        ->visible(fn(Consultation $record) => !in_array($record->status, [Consultation::STATUS_CANCELLED, Consultation::STATUS_REJECTED, Consultation::STATUS_COMPLETED])),
                    Tables\Actions\Action::make('upload_images')
                        ->label('Upload Images')
                        ->icon('heroicon-o-photo')
                        ->form([
                            Forms\Components\SpatieMediaLibraryFileUpload::make('media')
                                ->collection('consultations')
                                ->multiple()
                                ->maxFiles(10)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                                ->maxSize(5120)
                                ->label('Medical Images'),
                        ])
                        ->action(function (Consultation $record, array $data) {
                            Notification::make()
                                ->title('Images uploaded successfully')
                                ->success()
                                ->send();
                        })
                        ->modalHeading(fn(Consultation $record) => "Images for consultation #{$record->id}"),
                    Tables\Actions\Action::make('view_images')
                        ->label('View Images')
                        ->icon('heroicon-o-photo')
                        ->modalHeading(fn(Consultation $record) => "Images for consultation #{$record->id}")
                        ->modalContent(function (Consultation $record) {
                            $media = $record->media;
                            $content = '<div class="space-y-4">';

                            if ($media->count() > 0) {
                                $content .= '<div><h3 class="text-lg font-bold">Medical Images</h3><div class="grid grid-cols-2 gap-4 mt-2">';
                                foreach ($media as $item) {
                                    $imageUrl = $item->disk === 's3' ? $item->getTemporaryUrl(now()->addMinutes(20)) : $item->getFullUrl();
                                    $thumbUrl = $item->hasGeneratedConversion('small')
                                        ? ($item->conversions_disk === 's3' ? $item->getTemporaryUrl(now()->addMinutes(20), 'small') : $item->getFullUrl('small'))
                                        : $imageUrl;

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
                        ->visible(fn(Consultation $record): bool => $record->media->count() > 0),
                    Tables\Actions\Action::make('manage_review')
                        ->label('Manage Review')
                        ->icon('heroicon-o-star')
                        ->color('warning')
                        ->modalHeading(fn(Consultation $record) => "Review for consultation #{$record->id}")
                        ->form([
                            Forms\Components\Grid::make()
                                ->schema([
                                    Forms\Components\TextInput::make('stars')
                                        ->label('Rating')
                                        ->numeric()
                                        ->minValue(1)
                                        ->maxValue(5)
                                        ->step(0.1)
                                        ->required()
                                        ->helperText('Enter a rating between 1 and 5 (e.g., 4.7)'),
                                    Forms\Components\Textarea::make('comment')
                                        ->label('Review Comment')
                                        ->placeholder('Patient feedback about the consultation...')
                                        ->columnSpan(2),
                                ])
                                ->columns(2),
                        ])
                        ->modalWidth('xl')
                        ->action(function (Consultation $record, array $data) {
                            $review = Review::firstOrNew([
                                'reviewable_id' => $record->id,
                                'reviewable_type' => get_class($record),
                            ]);
                            $review->user_id = $record->user_id;
                            $review->stars = $data['stars'];
                            $review->comment = $data['comment'];
                            $review->save();

                            Notification::make()
                                ->title('Review saved successfully')
                                ->success()
                                ->send();
                        })
                        ->visible(fn(Consultation $record) => $record->status === Consultation::STATUS_COMPLETED),
                    Tables\Actions\Action::make('view_review')
                        ->label('View Review')
                        ->icon('heroicon-o-star')
                        ->modalHeading(fn(Consultation $record) => "Review for consultation #{$record->id}")
                        ->modalContent(function (Consultation $record) {
                            $review = Review::where('reviewable_id', $record->id)
                                ->where('reviewable_type', get_class($record))
                                ->first();

                            if (!$review) {
                                return new \Illuminate\Support\HtmlString(
                                    '<div class="text-center py-4">No review available for this consultation.</div>'
                                );
                            }

                            $userName = $review->user ? $review->user->name : 'Unknown User';
                            $starsHtml = $this->generateStarRating($review->stars);

                            $content = '<div class="space-y-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-medium">' . htmlspecialchars($userName) . '</h3>
                                        <p class="text-sm text-gray-500">' . $review->created_at->format('M d, Y') . '</p>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400">
                                            ' . $starsHtml . '
                                        </div>
                                        <span class="text-lg ml-1">(' . $review->stars . '/5)</span>
                                    </div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-gray-700">' . nl2br(htmlspecialchars($review->comment)) . '</p>
                                </div>
                            </div>';

                            return new \Illuminate\Support\HtmlString($content);
                        })
                        ->modalSubmitAction(false)
                        ->modalCancelAction(false)
                        ->visible(fn(Consultation $record) => Review::where('reviewable_id', $record->id)
                            ->where('reviewable_type', get_class($record))
                            ->exists()),
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
                    Tables\Actions\BulkAction::make('complete_selected')
                        ->label('Mark Selected as Completed')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->update(['status' => Consultation::STATUS_COMPLETED]);
                            Notification::make()
                                ->title('Selected Consultations Marked as Completed')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    /**
     * Generate HTML for star rating display
     * 
     * @param float $rating The rating value (e.g., 4.5)
     * @return string HTML content for displaying stars
     */
    private function generateStarRating($rating)
    {
        $html = '';
        $fullStars = floor($rating);
        $halfStar = ($rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
        
        // Full stars
        for ($i = 0; $i < $fullStars; $i++) {
            $html .= '<svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                      </svg>';
        }
        
        // Half star
        if ($halfStar) {
            $html .= '<svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <defs>
                          <linearGradient id="half-star" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="50%" stop-color="currentColor"/>
                            <stop offset="50%" stop-color="#d1d5db"/>
                          </linearGradient>
                        </defs>
                        <path fill="url(#half-star)" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                      </svg>';
        }
        
        // Empty stars
        for ($i = 0; $i < $emptyStars; $i++) {
            $html .= '<svg class="w-5 h-5 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                      </svg>';
        }
        
        return $html;
    }
}