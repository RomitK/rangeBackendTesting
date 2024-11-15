<?php

namespace App\Observers;

use App\Actions\RevalidationHandler\CampaignRevalidationHandlerAction;
use App\Actions\RevalidationHandler\WebsiteRevalidationHandlerAction;
use App\Enums\RevalidationHandler\TagEnum;
use App\Models\Career;

class CareerObserver
{
    protected WebsiteRevalidationHandlerAction $websiteAction;
    protected CampaignRevalidationHandlerAction $campaignAction;

    public function __construct(
        WebsiteRevalidationHandlerAction $websiteAction,
        CampaignRevalidationHandlerAction $campaignAction
    )
    {
        $this->websiteAction = $websiteAction;
        $this->campaignAction = $campaignAction;
    }
    public function created(Career $career): void
    {
        $this->websiteAction->execute(TagEnum::Careers()->value, $career->slug);
        $this->campaignAction->execute(TagEnum::Careers()->value, $career->slug);
    }

    public function updated(Career $career): void
    {
        $this->websiteAction->execute(TagEnum::Careers()->value, $career->slug);
        $this->campaignAction->execute(TagEnum::Careers()->value, $career->slug);
    }

    public function deleted(Career $career): void
    {
        $this->websiteAction->execute(TagEnum::Careers()->value, $career->slug);
        $this->campaignAction->execute(TagEnum::Careers()->value, $career->slug);
    }

}
