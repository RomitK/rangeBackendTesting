<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

class DataExportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $excelFile;

    public function __construct(File $excelFile)
    {
        $this->excelFile = $excelFile;
    }

    public function build()
    {
        return $this->view('mails.data-export')
            ->attach($this->excelFile, ['as' => 'data.xlsx']);
    }
}
