<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataExport;
use App\Mail\DataExportMail;
use Illuminate\Support\Facades\Log;
use App\Models\Project;

class ProjectExportAndEmailData implements ShouldQueue
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
        // Export data to Excel using Laravel Excel
        $export = Project::active()->latest()->get();

        Log::info('ProjectExportAndEmailData');
        Log::info($export);

        $excelFile = Excel::download($export, 'data.xlsx')->getFile();

        // Send email with the exported data as attachment
        Mail::to('aqsa@xpertise.ae')
            ->send(new DataExportMail($excelFile));
    }
}
