<?php

namespace App\Observers;

use App\Actions\RevalidationHandler\CampaignRevalidationHandlerAction;
use App\Actions\RevalidationHandler\WebsiteRevalidationHandlerAction;
use App\Enums\RevalidationHandler\TagEnum;
use App\Models\Agent;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;

class TestimonialObserver
{
    protected WebsiteRevalidationHandlerAction $websiteAction;
    protected CampaignRevalidationHandlerAction $campaignAction;
    public $afterCommit = true;

    public function __construct(
        WebsiteRevalidationHandlerAction $websiteAction,
        CampaignRevalidationHandlerAction $campaignAction
    )
    {
        $this->websiteAction = $websiteAction;
        $this->campaignAction = $campaignAction;
    }
    public function created(Testimonial $testimonial): void
    {
        $this->websiteAction->execute(TagEnum::Testimonials()->value);
        $this->campaignAction->execute(TagEnum::Testimonials()->value);
    }

    public function updated(Testimonial $testimonial): void
    {
        $this->websiteAction->execute(TagEnum::Testimonials()->value);
        $this->campaignAction->execute(TagEnum::Testimonials()->value);

        $attributesToCheck = ['feedback', 'client_name', 'rating', 'status', 'deleted_at'];
        if ($testimonial->isDirty($attributesToCheck)) {
            Cache::forget('homeTestimonials');
        }

    }
    public function deleted(Testimonial $testimonial): void
    {
        $this->websiteAction->execute(TagEnum::Testimonials()->value);
        $this->campaignAction->execute(TagEnum::Testimonials()->value);
    }
}
