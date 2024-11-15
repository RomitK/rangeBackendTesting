<?php

namespace App\Observers;

use App\Actions\RevalidationHandler\CampaignRevalidationHandlerAction;
use App\Actions\RevalidationHandler\WebsiteRevalidationHandlerAction;
use App\Enums\RevalidationHandler\TagEnum;
use App\Models\Community;
use Illuminate\Support\Facades\Cache;

class CommunityObserver
{
    protected WebsiteRevalidationHandlerAction $websiteAction;
    protected CampaignRevalidationHandlerAction $campaignAction;

    public $afterCommit = true;

    public function created(Community $community): void
    {
        $this->websiteAction->execute(TagEnum::Community()->value, $community->slug);
        $this->campaignAction->execute(TagEnum::Community()->value, $community->slug);
    }
    public function updated(Community $community): void
    {
        $attributesToCheck = ['name', 'slug', 'banner_image', 'status', 'is_approved', 'display_on_home', 'communityOrder', 'deleted_at'];
        if ($community->isDirty($attributesToCheck)) {
            Cache::forget('homeCommunities');
        }

        $this->websiteAction->execute(TagEnum::Community()->value, $community->slug);
        $this->campaignAction->execute(TagEnum::Community()->value, $community->slug);
    }
    public function deleted(Community $community): void
    {
        $this->websiteAction->execute(TagEnum::Community()->value, $community->slug);
        $this->campaignAction->execute(TagEnum::Community()->value, $community->slug);
    }
}
