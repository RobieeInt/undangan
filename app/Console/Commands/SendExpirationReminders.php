<?php

namespace App\Console\Commands;

use App\Mail\ExpirationReminderEmail;
use App\Models\Invitation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendExpirationReminders extends Command
{
    protected $signature   = 'invitations:send-expiration-reminders';
    protected $description = 'Send email reminders for invitations expiring in 14 days';

    public function handle(): void
    {
        $invitations = Invitation::with('user')
            ->where('is_active', true)
            ->whereBetween('expires_at', [now()->addDays(13), now()->addDays(15)])
            ->get();

        foreach ($invitations as $invitation) {
            Mail::to($invitation->user->email)
                ->queue(new ExpirationReminderEmail($invitation));
        }

        $this->info("Sent {$invitations->count()} expiration reminders.");
    }
}
