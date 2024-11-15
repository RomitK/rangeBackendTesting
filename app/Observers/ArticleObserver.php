<?php

namespace App\Observers;

use App\Actions\RevalidationHandler\CampaignRevalidationHandlerAction;
use App\Actions\RevalidationHandler\WebsiteRevalidationHandlerAction;
use App\Enums\RevalidationHandler\TagEnum;
use App\Models\Article;

class ArticleObserver
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
    public function created(Article $article): void
    {
        $this->websiteAction->execute(TagEnum::Media()->value, $article->slug);
        $this->campaignAction->execute(TagEnum::Media()->value, $article->slug);
    }

    public function updated(Article $article): void
    {
        $this->websiteAction->execute(TagEnum::Media()->value, $article->slug);
        $this->campaignAction->execute(TagEnum::Media()->value, $article->slug);
    }

    public function deleted(Article $article): void
    {
        $this->websiteAction->execute(TagEnum::Media()->value, $article->slug);
        $this->campaignAction->execute(TagEnum::Media()->value, $article->slug);
    }

}
