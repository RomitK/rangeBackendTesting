<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Exports\PropertyLogDataExport;
use App\Mail\PropertyLogExportMail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class PropertyLogExportAndEmailData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $export = new PropertyLogDataExport($this->collection);
        $excelFile = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);

        // Log the export data (optional)
        Log::info('ProjectLogExportAndEmailData');

        // Send email with the exported data as attachment
        if ($this->request['email'] === 'admin@gmail.com') {
            $email = 'aqsa@xpertise.ae';
        } else {
            $email = $this->request['email'];
        }
        Mail::to($email)->send(new PropertyLogExportMail($excelFile, $this->request['userName']));
    }
}
