<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Models\{
    Project,
    Community,
    Property,
    Developer,
    Article,
    Career,
    Guide,
    Agent
};

class WeeklyWebsiteStateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('WeeklyWebsiteStateReportJob Start');
        try {
            $recipients = [
                ['name' => 'Aqsa', 'email' => 'aqsa@xpertise.ae'],
                //['name' => 'Nitin Chopra', 'email' => 'nitin@range.ae'],
                // ['name' => 'Lester Verma', 'email' => 'lester@range.ae'],
                ['name' => 'Romit Kumar', 'email' => 'romit@range.ae'],
                ['name' => 'Safeena Ahmad', 'email' => 'safeeena@xpertise.ae'],
            ];

            sendWebsiteStatReport($recipients);
        } catch (\Exception $error) {
            Log::info("WeeklyWebsiteStateReportJob-error" . $error->getMessage());
        }
    }
}
