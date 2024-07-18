<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgentDataExportMail extends  Mailable
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
        return $this->view('mails.agent-data-export')
            ->subject('Agents Data')
            ->attachData($this->excelFile, 'Agents.xlsx', [
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->with('userName', $this->userName);
    }
}
