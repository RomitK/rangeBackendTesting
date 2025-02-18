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
        $payload = $this->job->payload();
        $jobName = $payload['displayName'] ?? class_basename($payload['data']['commandName']);

        return (new MailMessage)
            ->subject('Queue Job Failed')
            ->line('A job has failed.')
            ->line('Job Name: ' . $jobName)  // Include the job name here
            ->line('Job ID: ' . $this->job->getJobId())
            ->line('Exception: ' . $this->exception->getMessage())
            ->line('Thank you for using our application!');
    }
}
