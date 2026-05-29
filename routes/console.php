<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('invitations:send-expiration-reminders')->dailyAt('08:00');
Schedule::command('invitations:disable-expired')->hourly();
Schedule::command('payments:cleanup-pending')->hourly();
Schedule::command('queue:work --stop-when-empty')->everyFiveMinutes();
