<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('cj:checkOrders')->everyMinute();
        $schedule->command('check:shopOrders')->everyMinute();
        $schedule->command('update:cj_token')->everyMinute();
        $schedule->command('reactivate:shops')->monthly();
        $schedule->command('delete:resetPasswords')->everyMinute();
        $schedule->command('storeOwner:ImportCJProducts')->withoutOverlapping()->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
