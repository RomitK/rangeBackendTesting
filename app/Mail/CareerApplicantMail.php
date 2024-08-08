<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CareerApplicantMail extends Mailable
{
    use Queueable, SerializesModels;

    public $excelFile, $data;


    public function __construct($excelFile, $data)
    {
        $this->excelFile = $excelFile;
        $this->data = $data;
    }

    public function build()
    {
        return $this->view('mails.career-applicant')
            ->subject('New CV Received')
            ->with('data', $this->data);
    }
}
