<?php

namespace App\Listeners;

use App\Notifications\JobFailedNotification;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendJobFailedNotification
{
    public function __construct()
    {
        //
    }

    public function handle(JobFailed $event)
    {
        $notifiable = new \stdClass; // Creating a dummy notifiable object
        $notifiable->email = 'aqsa@xpertise.ae'; // Set your email here

        Notification::route('mail', $notifiable->email)->notify(new JobFailedNotification($event->job, $event->exception));
    }
}
