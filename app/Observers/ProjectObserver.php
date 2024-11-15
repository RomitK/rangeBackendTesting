<?php

namespace App\Observers;

use App\Actions\RevalidationHandler\CampaignRevalidationHandlerAction;
use App\Actions\RevalidationHandler\WebsiteRevalidationHandlerAction;
use App\Enums\RevalidationHandler\TagEnum;
use App\Models\Project;

class ProjectObserver
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
    public function created(Project $project): void
    {
        $this->websiteAction->execute(TagEnum::Projects()->value, $project->slug);
        $this->campaignAction->execute(TagEnum::Projects()->value, $project->slug);
    }
    public function updated(Project $project): void
    {
        $this->websiteAction->execute(TagEnum::Projects()->value, $project->slug);
        $this->campaignAction->execute(TagEnum::Projects()->value, $project->slug);
    }

    public function deleted(Project $project): void
    {
        $this->websiteAction->execute(TagEnum::Projects()->value, $project->slug);
        $this->campaignAction->execute(TagEnum::Projects()->value, $project->slug);
    }
}
