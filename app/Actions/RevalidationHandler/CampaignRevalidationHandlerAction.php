<?php

namespace App\Actions\RevalidationHandler;

use Illuminate\Support\Facades\Http;

/**
 * @property string $websiteUrl
 * @property string $token
 */
class CampaignRevalidationHandlerAction
{
    public function __construct()
    {
        $this->websiteUrl = config('services.revalidate_api.range_campaign_url');
        $this->token = config('services.revalidate_api.range_campaign_revalidate_token');
    }

    public function execute(string $tag, ?string $slug = null): void
    {
        if (empty($this->websiteUrl) || empty($this->token)) {
            return;
        }

        try {
            Http::withHeaders(['X-Revalidate-Key' => $this->token])
                ->post($this->websiteUrl, ['tags' => array_filter([$tag, $slug ? $tag . ':' . $slug : null])]);
        } catch (\Exception $exception) {
            info($exception->getMessage());
        }
    }

    public function executeSetsOfTags(array $tags): void
    {
        if (empty($this->websiteUrl) || empty($this->token)) {
            return;
        }
        
        try {
            Http::withHeaders(['X-Revalidate-Key' => $this->token])
                ->post($this->websiteUrl, ["tags" => $tags]);
        } catch (\Exception $exception) {
            info($exception->getMessage());
        }
    }
}
