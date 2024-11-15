<?php

namespace App\Observers;

use App\Actions\RevalidationHandler\CampaignRevalidationHandlerAction;
use App\Actions\RevalidationHandler\WebsiteRevalidationHandlerAction;
use App\Enums\RevalidationHandler\TagEnum;
use App\Models\Agent;

class AgentObserver
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
    public function created(Agent $agent): void
    {
        $this->websiteAction->execute(TagEnum::Agents()->value);
        $this->campaignAction->execute(TagEnum::Agents()->value);
    }

    public function updated(Agent $agent): void
    {
        $this->websiteAction->execute(TagEnum::Agents()->value);
        $this->campaignAction->execute(TagEnum::Agents()->value);
    }

    public function deleted(Agent $agent): void
    {
        $this->websiteAction->execute(TagEnum::Agents()->value);
        $this->campaignAction->execute(TagEnum::Agents()->value);
    }

}
