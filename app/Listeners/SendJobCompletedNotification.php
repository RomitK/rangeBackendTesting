<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\JobCompletedNotification;

class SendJobCompletedNotification
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

        Notification::route('mail', $notifiable->email)->notify(new JobCompletedNotification($event->job));
    }
}
