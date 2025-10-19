<?php

namespace App\Nova\Actions;

use App\Enums\SupportMessageSenderType;
use App\Enums\SupportMessageStatus;
use App\Models\SupportMessage;
use App\Notifications\ReplySupportMessageNotification;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class ReplyToTicket extends Action
{
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $ticket) {
            $reply = SupportMessage::create([
                'parent_id' => $ticket->id,
                'subject' => 'Reply for' . $ticket->subject,
                'body' => $fields->body,
                'user_id' => auth()->id(),
                'phone' => '',
                'sender_type' => SupportMessageSenderType::Admin,
                'status' => SupportMessageStatus::Reply,
            ]);

            $ticket->markAsResolved();
            $ticket->user->notify(new ReplySupportMessageNotification($reply));
        }

        return Action::message('Reply sent and ticket marked as resolved.');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Textarea::make(__('message'), 'body'),
        ];
    }
}
