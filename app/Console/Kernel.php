<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Jobs\{
    WeeklyWebsiteStateReportJob,
    MonthlyWebsiteStateReportJob,
    InactivePropertyJob
};

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // $schedule->call(function () {
        //     Log::info('UpdateAgentResponOnLead run at-' . Carbon::now());
        // })->everyMinute();

        $schedule->job(new LatestCurrencyJob)->daily()->at('01:00');
        
        $schedule->job(new WeeklyWebsiteStateReportJob)->weeklyOn(1, '9:00')->timezone('Asia/Dubai');
        $schedule->job(new MonthlyWebsiteStateReportJob)->monthly()->at('9:00')->timezone('Asia/Dubai');


        // $schedule->job(new InactivePropertyJob)->daily()->at('02:00')->timezone('Asia/Dubai');

        $schedule->command('backup:clean')->daily()->at('01:00');
        $schedule->command('backup:run')->daily()->at('01:30');

        //$schedule->job(new MonthlyWebsiteStateReportJob)->everyTwoMinutes();
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
