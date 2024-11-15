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

    public function execute(string $tag): void
    {
        if (empty($this->websiteUrl) || empty($this->token)) {
            return;
        }

        try {
            Http::withHeaders(['key' => $this->token])
                ->post($this->websiteUrl, ['tags' => $tag]);
        } catch (\Exception $exception) {
            info($exception->getMessage());
        }
    }
}
