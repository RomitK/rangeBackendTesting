<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PropertyDataExportMail extends Mailable
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
        return $this->view('mails.property-data-export')
            ->subject('Properties Data')
            ->attachData($this->excelFile, 'Properties.xlsx', [
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->with('userName', $this->userName);
    }
}
