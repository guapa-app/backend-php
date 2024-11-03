<?php

namespace App\Filament\Admin\Resources\UserVendor\SupportMessageResource\Actions;

use App\Enums\SupportMessageSenderType;
use App\Enums\SupportMessageStatus;
use App\Notifications\ReplySupportMessageNotification;
use Filament\Forms;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class ReplyToTicketAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Reply to Ticket')
            ->form([
                Forms\Components\Textarea::make('body')
                    ->label('Reply Message')
                    ->required(),
            ])
            ->action(function (array $data, $record) {
                $reply = $record->replies()->create([
                    'body' => $data['body'],
                    'user_id' => auth()->id(),
                    'parent_id' => $record->id,
                    'subject' => 'Reply for' . $record->subject,
                    'phone' => '',
                    'sender_type' => SupportMessageSenderType::Admin,
                    'status' => SupportMessageStatus::Reply,
                ]);

                $record->markAsResolved();
                $record->user->notify(new ReplySupportMessageNotification($reply));
            })
            ->button()
            ->visible(fn(Model $record) => $record->status === SupportMessageStatus::Pending)
            ->icon('heroicon-o-rectangle-stack')
            ->modalHeading('Reply to Ticket')
            ->modalSubmitActionLabel('Send Reply');
    }
}
