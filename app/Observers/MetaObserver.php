<?php

namespace App\Observers;

use App\Actions\RevalidationHandler\CampaignRevalidationHandlerAction;
use App\Actions\RevalidationHandler\WebsiteRevalidationHandlerAction;
use App\Enums\RevalidationHandler\TagEnum;
use App\Models\PageTag;

class MetaObserver
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
    public function created(PageTag $pageTag): void
    {
        $this->websiteAction->execute(TagEnum::Meta()->value, $pageTag->page_name);
        $this->campaignAction->execute(TagEnum::Meta()->value, $pageTag->page_name);
    }

    public function updated(PageTag $pageTag): void
    {
        $this->websiteAction->execute(TagEnum::Meta()->value, $pageTag->page_name);
        $this->campaignAction->execute(TagEnum::Meta()->value, $pageTag->page_name);
    }

    public function deleted(PageTag $pageTag): void
    {
        $this->websiteAction->execute(TagEnum::Meta()->value, $pageTag->page_name);
        $this->campaignAction->execute(TagEnum::Meta()->value, $pageTag->page_name);
    }

}
