<?php

namespace App\Observers;

use App\Actions\RevalidationHandler\CampaignRevalidationHandlerAction;
use App\Actions\RevalidationHandler\WebsiteRevalidationHandlerAction;
use App\Enums\RevalidationHandler\TagEnum;
use App\Models\Faq;

class FaqObserver
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
    public function created(Faq $faq): void
    {
        $this->websiteAction->execute(TagEnum::Faq()->value);
        $this->campaignAction->execute(TagEnum::Faq()->value);
    }

    public function updated(Faq $faq): void
    {
        $this->websiteAction->execute(TagEnum::Faq()->value);
        $this->campaignAction->execute(TagEnum::Faq()->value);
    }

    public function deleted(Faq $faq): void
    {
        $this->websiteAction->execute(TagEnum::Faq()->value);
        $this->campaignAction->execute(TagEnum::Faq()->value);
    }

}
