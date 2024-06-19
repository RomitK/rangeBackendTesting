<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommunityLogExportMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $excelFile, $userName;
    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($excelFile, $userName)
    {
        $this->excelFile = $excelFile;
        $this->userName = $userName;
    }

    public function build()
    {
        return $this->view('mails.community-log-export')
            ->subject('Community Log Data')
            ->attachData($this->excelFile, 'Community.xlsx', [
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->with('userName', $this->userName);
    }
}
