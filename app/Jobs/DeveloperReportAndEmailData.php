<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Exports\DeveloperReportExport;
use App\Mail\DeveloperDataExportMail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class DeveloperReportAndEmailData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('developerReport');

        $export = new DeveloperReportExport($this->data);
        $excelFile = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);

        // Send email with the exported data as attachment
        if ($this->data['email'] === 'admin@gmail.com') {
            $email = 'aqsa@xpertise.ae';
        } else {
            $email = $this->data['email'];
        }
        Mail::to($email)->send(new DeveloperDataExportMail($excelFile, $this->data['userName']));
    }
}
