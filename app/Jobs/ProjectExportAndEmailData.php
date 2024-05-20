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
use App\Exports\ProjectDataExport;
use App\Mail\ProjectDataExportMail;
use Illuminate\Support\Facades\Log;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectExportAndEmailData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $export = new ProjectDataExport($this->request);
        //$export = $export->query()->latest()->limit(1)->get();
        $excelFile = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);

        // Log the export data (optional)
        Log::info('ProjectExportAndEmailData');
        //Log::info($export);

        // Send email with the exported data as attachment
        if ($this->request['email'] === 'admin@gmail.com') {
            $email = 'aqsa@xpertise.ae';
        } else {
            $email = $this->request['email'];
        }
        Mail::to($email)->send(new ProjectDataExportMail($excelFile, $this->request['userName']));
    }
}
