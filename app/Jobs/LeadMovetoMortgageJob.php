<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LeadMovetoMortgageJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('MortgageLeadJob Start');
        Log::info($this->data);
        try {

            $response = Http::withHeaders([
                'authorization-token' => config('mortgage_lead_token'),
            ])->post(config('app.mortgage_lead_api'), $this->data);


            if ($response->successful()) {
                // Request was successful, handle the response
                $responseData = $response->json(); // If expecting JSON response
                Log::info('MortgageLeadJob DONE');
                Log::info($responseData);
                // Process the response data here
            } else {
                // Request failed, handle the error
                $errorCode = $response->status();
                $errorMessage = $response->body(); // Get the error message
                // Handle the error here

                Log::info('MortgageLeadJob ERROR DONE');
                Log::info($errorMessage);
            }
        } catch (\Exception $exception) {
            Log::info('MortgageLeadJob ERROR DONE');
            Log::info($exception->getMessage());
        }
    }
}
