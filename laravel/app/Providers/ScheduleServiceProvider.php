<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class ScheduleServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(Schedule $schedule): void
    {
        // $schedule->command('app:sendUpcomingServiceMails')->dailyAt('07:00');

        $schedule->command('fetch:PpmsData')->everyThirtyMinutes();
    }
}
