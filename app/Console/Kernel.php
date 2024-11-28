<?php

namespace Sibas\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Sibas\Console\Commands\AppMigrate;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        AppMigrate::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*$schedule->command('inspire')
                 ->hourly();*/

        // $schedule->call('\Sibas\Http\Controllers\API\ElpCollectionController@export')->dailyAt('17:45');

        // $schedule->call('\Sibas\Http\Controllers\API\ElpCollectionController@importConfirm')->dailyAt('09:30');

        // $schedule->call('\Sibas\Http\Controllers\API\ElpCollectionController@importProduction')->dailyAt('09:45');

        //$schedule->command('report:attachments')->everyMinute();
        //$schedule->command('report:co-debtor')->everyMinute();
    }
}
