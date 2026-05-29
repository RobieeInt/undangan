<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DisableExpiredInvitations extends Command
{
    protected $signature   = 'invitations:disable-expired';
    protected $description = 'Disable invitations that have passed their expiry date';

    public function handle(): void
    {
        $count = DB::table('invitations')
            ->where('is_active', true)
            ->where('expires_at', '<', now())
            ->update([
                'is_active'    => false,
                'is_published' => false,
                'updated_at'   => now(),
            ]);

        $this->info("Disabled {$count} expired invitations.");
    }
}
