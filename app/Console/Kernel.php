<?php

namespace Sibas\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Sibas\Console\Commands\Inspire;
use Sibas\Console\Commands\ReportAttachments;
use Sibas\Console\Commands\ReportCoDebtor;
use Sibas\Console\Commands\SendTjCompany;
use Sibas\Console\Commands\UpdateTjCollections;
use Sibas\Console\Commands\SendTjCollectionsToFortaleza;
use Sibas\Console\Commands\UpdateWsClients;
use Sibas\Console\Commands\UpdateDataTable;
use Sibas\Console\Commands\UpdateAgencies;
use Sibas\Console\Commands\SetNewCollections;
use Sibas\Console\Commands\NewVersionCert;
use Sibas\Console\Commands\SendDeIssued;
use Sibas\Console\Commands\SetNewRateAuTd;
use Sibas\Console\Commands\StoreTjCollectionByXls;
use Sibas\Console\Commands\TjMigrate;
use Sibas\Console\Commands\AuMigrate;
use Sibas\Console\Commands\AppMigrate;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Inspire::class,
        ReportAttachments::class,
        ReportCoDebtor::class,
        SendTjCompany::class,
        UpdateTjCollections::class,
        SendTjCollectionsToFortaleza::class,
        UpdateWsClients::class,
        UpdateDataTable::class,
        UpdateAgencies::class,
        SetNewCollections::class,
        NewVersionCert::class,
        SendDeIssued::class,
        SetNewRateAuTd::class,
        StoreTjCollectionByXls::class,
        TjMigrate::class,
        AuMigrate::class,
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
