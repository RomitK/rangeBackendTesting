<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Facades\Auth;

class ProjectDataExportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $excelFile, $userName;


    public function __construct($excelFile, $userName)
    {
        $this->excelFile = $excelFile;
        $this->userName = $userName;
    }

    public function build()
    {
        return $this->view('mails.project-data-export')
            ->subject('Projects Data')
            ->attachData($this->excelFile, 'Projects.xlsx', [
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->with('userName', $this->userName);
    }
}
