<?php

namespace App\Console\Commands;

use App\Models\Consultation;
use App\Notifications\MeetingInvitationNotification;
use Illuminate\Console\Command;

class SendTestMeetingEmail extends Command
{
    protected $signature = 'email:test-meeting {consultation_id}';
    protected $description = 'Send a test meeting invitation email for a specific consultation';

    public function handle()
    {
        $consultationId = $this->argument('consultation_id');
        $consultation = Consultation::findOrFail($consultationId);

        $this->info('Sending test email notifications for consultation #' . $consultationId);
        
        // Send to user
        $consultation->user->notify(new MeetingInvitationNotification($consultation, false));
        $this->info('Email sent to user: ' . $consultation->user->email);
        
        // Send to vendor
        $consultation->vendor->notify(new MeetingInvitationNotification($consultation, true));
        $this->info('Email sent to vendor: ' . $consultation->vendor->email);
        
        return 0;
    }
} 