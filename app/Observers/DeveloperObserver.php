<?php

namespace App\Observers;

use App\Actions\RevalidationHandler\CampaignRevalidationHandlerAction;
use App\Actions\RevalidationHandler\WebsiteRevalidationHandlerAction;
use App\Enums\RevalidationHandler\TagEnum;
use App\Models\Developer;
use Illuminate\Support\Facades\Cache;

class DeveloperObserver
{
    protected WebsiteRevalidationHandlerAction $websiteAction;
    protected CampaignRevalidationHandlerAction $campaignAction;
    public bool $afterCommit = true;

    public function __construct(
        WebsiteRevalidationHandlerAction $websiteAction,
        CampaignRevalidationHandlerAction $campaignAction
    )
    {
        $this->websiteAction = $websiteAction;
        $this->campaignAction = $campaignAction;
    }

    public function created(Developer $developer): void
    {
        $this->websiteAction->execute(TagEnum::Property()->value, $developer->slug);
        $this->campaignAction->execute(TagEnum::Property()->value, $developer->slug);
    }

    public function updated(Developer $developer): void
    {
        $this->websiteAction->execute(TagEnum::Property()->value, $developer->slug);
        $this->campaignAction->execute(TagEnum::Property()->value, $developer->slug);

        $attributesToCheck = ['name', 'slug', 'logo_image', 'status', 'is_approved', 'display_on_home', 'developerOrder', 'deleted_at'];
        if ($developer->isDirty($attributesToCheck)) {
            Cache::forget('homeDevelopers');
        }
    }

    public function deleted(Developer $developer): void
    {
        $this->websiteAction->execute(TagEnum::Property()->value, $developer->slug);
        $this->campaignAction->execute(TagEnum::Property()->value, $developer->slug);
    }
}
