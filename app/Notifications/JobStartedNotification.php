<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobStartedNotification extends Notification
{
    use Queueable;

    protected $job;

    public function __construct($job)
    {
        $this->job = $job;
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
            ->subject('Queue Job Started')
            ->line('A job has started.')
            ->line('Job Name: ' . $jobName)  // Include the job name here
            ->line('Job ID: ' . $this->job->getJobId())
            ->line('Thank you for using our application!');
    }
}
