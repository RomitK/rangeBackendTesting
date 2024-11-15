<?php

namespace App\Actions\RevalidationHandler;
use Illuminate\Support\Facades\Http;

/**
 * @property string $websiteUrl
 * @property string $token
 */
class WebsiteRevalidationHandlerAction
{
    public function __construct()
    {
        $this->websiteUrl = config('services.revalidate_api.range_website_url');
        $this->token = config('services.revalidate_api.range_website_revalidate_token');
    }

    public function execute(string $tag, ?string $slug = null): void
    {
        if (empty($this->websiteUrl) || empty($this->token)) {
            return;
        }

        try {
              Http::withHeaders(['X-Revalidate-Key' => $this->token])
                  ->post($this->websiteUrl, ['tags' => [$tag, $tag . ':' . $slug]]);
        } catch (\Exception $exception) {
            info($exception->getMessage());
        }

    }
}
