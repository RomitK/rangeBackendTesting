<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class LatestCurrencyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("LatestCurrencyJob-start-time: " . Carbon::now());
        DB::beginTransaction();
        try {
            
            // Define the API URL and parameters
            $apiUrl = 'https://api.fastforex.io/convert';
            $apiKey = '0729fcd7c5-ddde638fbf-si5nsk';
            $fromCurrency = 'AED';
            $toCurrency = 'INR';
            $amount = 1;

            // Make the GET request
            $response = Http::get($apiUrl, [
                'from' => $fromCurrency,
                'to' => $toCurrency,
                'amount' => $amount,
                'api_key' => $apiKey,
            ]);

            // Check if the response was successful
            if ($response->successful()) {
                // Decode the JSON response
                $data = $response->json();

                // Access the conversion result
                $convertedAmount = $data['result'][$toCurrency] ?? null;

                WebsiteSetting::setSetting(config('constants.INR_Currency'),  $convertedAmount);

            } else {
                // Handle error
                echo "Error: " . $response->status();
            }

            DB::commit();
        } catch (\Exception $error) {
            Log::info("LatestCurrencyJob-error" . $error->getMessage());
        }

        Log::info("LatestCurrencyJob-end-time: " . Carbon::now());
    }
}
