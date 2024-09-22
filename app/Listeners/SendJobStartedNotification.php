<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\JobStartedNotification;

class SendJobStartedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $notifiable = new \stdClass; // Creating a dummy notifiable object
        $notifiable->email = 'aqsa@xpertise.ae'; // Set your email here

        Notification::route('mail', $notifiable->email)->notify(new JobStartedNotification($event->job));
    }
}
