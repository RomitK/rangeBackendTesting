<?php

namespace App\Observers;

use App\Models\Developer;
use Illuminate\Support\Facades\Cache;

class DeveloperObserver
{
    public $afterCommit = true;
    /**
     * Handle the Developer "updated" event.
     *
     * @param  \App\Models\Developer  $developer
     * @return void
     */
    public function updated(Developer $developer)
    {
        $attributesToCheck = ['name', 'slug', 'logo_image', 'status', 'is_approved', 'display_on_home', 'developerOrder', 'deleted_at'];
        if ($developer->isDirty($attributesToCheck)) {
            Cache::forget('homeDevelopers');
        }
    }
}
