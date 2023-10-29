<?php

namespace App\Observers;

use App\Models\WorkDay;

class WorkDayObserver
{
    /**
     * Handle the WorkDay "creating" event.
     */
    public function creating(WorkDay $workDay)
    {
        WorkDay::where([
            'vendor_id' => $workDay->vendor_id,
            'day' => $workDay->day,
        ])->delete();
    }

    /**
     * Handle the WorkDay "updating" event.
     */
    public function updating(WorkDay $workDay): void
    {
        WorkDay::where([
            'vendor_id' => $workDay->vendor_id,
            'day' => $workDay->day,
        ])->delete();
    }
}
