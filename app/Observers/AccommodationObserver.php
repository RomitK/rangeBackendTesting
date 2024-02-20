<?php

namespace App\Observers;

use App\Models\Accommodation;

class AccommodationObserver
{
    /**
     * Handle the Accommodation "created" event.
     *
     * @param  \App\Models\Accommodation  $accommodation
     * @return void
     */
    public function created(Accommodation $accommodation)
    {
        //
    }

    /**
     * Handle the Accommodation "updated" event.
     *
     * @param  \App\Models\Accommodation  $accommodation
     * @return void
     */
    public function updated(Accommodation $accommodation)
    {
        //
    }

    /**
     * Handle the Accommodation "deleted" event.
     *
     * @param  \App\Models\Accommodation  $accommodation
     * @return void
     */
    public function deleted(Accommodation $accommodation)
    {
        //
    }

    /**
     * Handle the Accommodation "restored" event.
     *
     * @param  \App\Models\Accommodation  $accommodation
     * @return void
     */
    public function restored(Accommodation $accommodation)
    {
        //
    }

    /**
     * Handle the Accommodation "force deleted" event.
     *
     * @param  \App\Models\Accommodation  $accommodation
     * @return void
     */
    public function forceDeleted(Accommodation $accommodation)
    {
        //
    }
}
