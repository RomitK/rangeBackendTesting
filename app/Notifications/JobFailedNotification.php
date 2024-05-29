<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobFailedNotification extends Notification
{
    use Queueable;

    protected $job;
    protected $exception;

    public function __construct($job, $exception)
    {
        $this->job = $job;
        $this->exception = $exception;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Queue Job Failed')
            ->line('A job has failed.')
            ->line('Job ID: ' . $this->job->getJobId())
            ->line('Exception: ' . $this->exception->getMessage())
            ->line('Thank you for using our application!');
    }
}
