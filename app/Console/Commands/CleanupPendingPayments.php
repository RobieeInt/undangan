<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupPendingPayments extends Command
{
    protected $signature   = 'payments:cleanup-pending';
    protected $description = 'Mark expired pending payments as expired';

    public function handle(): void
    {
        $count = DB::table('transactions')
            ->where('status', 'pending')
            ->where('expired_at', '<', now())
            ->update(['status' => 'expired', 'updated_at' => now()]);

        $this->info("Cleaned up {$count} expired pending payments.");
    }
}
