<?php

namespace App\Observers;

use App\Enums\SupportMessageStatus;
use App\Models\SupportMessage;

class SupportMessageObserver
{
    /**
     * Handle the SupportMessage "creating" event.
     */
    public function creating(SupportMessage $supportMessage): void
    {
        $supportMessage->status ??= SupportMessageStatus::Pending;
    }
}
