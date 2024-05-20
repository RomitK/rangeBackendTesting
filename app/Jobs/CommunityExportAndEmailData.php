<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Exports\CommunityDataExport;
use App\Mail\CommunityDataExportMail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;


class CommunityExportAndEmailData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $request, $collection;

    public function __construct($request, $collection)
    {
        $this->request = $request;
        $this->collection = $collection;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Log the export data (optional)
        Log::info('CommunityExportAndEmailData');

        $export = new CommunityDataExport($this->collection);
        $excelFile = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);



        // Send email with the exported data as attachment
        if ($this->request['email'] === 'admin@gmail.com') {
            $email = 'aqsa@xpertise.ae';
        } else {
            $email = $this->request['email'];
        }
        Mail::to($email)->send(new CommunityDataExportMail($excelFile, $this->request['userName']));
    }
}
