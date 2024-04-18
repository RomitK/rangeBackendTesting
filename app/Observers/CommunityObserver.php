<?php

namespace App\Observers;

use App\Models\Community;
use Illuminate\Support\Facades\Cache;

class CommunityObserver
{

    public $afterCommit = true;
    /**
     * Handle the Community "updated" event.
     *
     * @param  \App\Models\Community  $community
     * @return void
     */
    public function updated(Community $community)
    {
        $attributesToCheck = ['name', 'slug', 'banner_image', 'status', 'is_approved', 'display_on_home', 'communityOrder', 'deleted_at'];
        if ($community->isDirty($attributesToCheck)) {
            Cache::forget('homeCommunities');
        }
    }
}
