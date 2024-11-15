<?php

namespace App\Observers;

use App\Actions\RevalidationHandler\CampaignRevalidationHandlerAction;
use App\Actions\RevalidationHandler\WebsiteRevalidationHandlerAction;
use App\Enums\RevalidationHandler\TagEnum;
use App\Models\Property;

class PropertyObserver
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
    public function created(Property $property): void
    {
        $this->websiteAction->execute(TagEnum::Property()->value, $property->slug);
        $this->campaignAction->execute(TagEnum::Property()->value, $property->slug);
    }

    public function updated(Property $property): void
    {
        $this->websiteAction->execute(TagEnum::Property()->value, $property->slug);
        $this->campaignAction->execute(TagEnum::Property()->value, $property->slug);
    }

    public function deleted(Property $property): void
    {
        $this->websiteAction->execute(TagEnum::Property()->value, $property->slug);
        $this->campaignAction->execute(TagEnum::Property()->value, $property->slug);
    }

}
