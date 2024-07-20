<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        // Commands\SubscriptionChecker::class,
        // Commands\SubscriptionStarter::class,
        // Commands\UpdateCurrencyRate::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // currency update schedule
        $schedule->command('currencyRate:update')->everySixHours()->runInBackground();
        // currency update schedule end

        // $schedule->command('subscription:check')->everyThirtyMinutes();
        // $schedule->command('subscription:start')->everyFifteenMinutes();

        /** Add transactions paystack verification cron for invoices that have transaction references
         * Maybe payment went through but course wasn't activated or transactions wasn't updated.
         */

        /**
         * For every completed batch, if the student wants to continue paying for access, allow them. when their cycle is completed, return them to completed instead of expired
         *
         */

        /** Run every minute specified queue if not already started */
        // if (stripos((string) shell_exec('ps xf | grep \'[q]ueue:work\''), 'artisan queue:work') === false) {
        // }
        $schedule->command('queue:work --queue=default --sleep=2 --tries=3 --timeout=5')->everyMinute()->appendOutputTo(storage_path() . '/logs/laravel.log');

        /** Run every minute specified queue if not already started */
        // if (stripos((string) shell_exec('ps xf | grep \'[q]ueue:work\''), 'artisan queue:retry') === false) {
        // }



        if (config('app.env') === 'production') {
            $schedule->command('queue:retry all')->everyFourHours()->appendOutputTo(storage_path() . '/logs/laravel.log');
        } else $schedule->command('queue:retry all')->everyMinute()->appendOutputTo(storage_path() . '/logs/laravel.log');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
