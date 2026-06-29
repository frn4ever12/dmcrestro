<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule commands
Schedule::command('queue:work --stop-when-empty')->everyMinute();
Schedule::job(new \App\Jobs\CheckSubscriptionExpiry())->daily();
Schedule::job(new \App\Jobs\GenerateDailyReport(null, null))->dailyAt('23:59');
