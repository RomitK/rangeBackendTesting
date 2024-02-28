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

class CRMLeadJob implements ShouldQueue
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
        Log::info('CRMLeadJob Start');
        try {
            $token = '3MPHJP0BC63435345341';

            $response = Http::withHeaders([
                'authorization-token' => $token,
            ])->post('https://axtech.range.ae/api/v2/webLeads', $this->data);


            if ($response->successful()) {
                // Request was successful, handle the response
                $responseData = $response->json(); // If expecting JSON response
                Log::info('CRM DONE');
                Log::info($responseData);
                // Process the response data here
            } else {
                // Request failed, handle the error
                $errorCode = $response->status();
                $errorMessage = $response->body(); // Get the error message
                // Handle the error here

                Log::info('CRM ERROR DONE');
                Log::info($errorMessage);
            }
        } catch (\Exception $exception) {
            Log::info('CRM ERROR DONE');
            Log::info($exception->getMessage());
        }
    }
}
