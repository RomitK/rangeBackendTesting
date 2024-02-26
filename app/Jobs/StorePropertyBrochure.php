<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\{
    Property,
    Amenity,
    Accommodation,
    Category,
    CompletionStatus,
    Community,
    Developer,
    Feature,
    OfferType,
    Specification,
    Agent,
    PropertyBedroom,
    PropertyDetail,
    Subcommunity,
    Project,
    User
};
use Carbon\Carbon;
use PDF;
use DB;

class StorePropertyBrochure implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $propertyId;

    public function __construct($propertyId)
    {
        $this->propertyId = $propertyId;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("StorePropertySaleOffer-start-time: " . Carbon::now());
        Log::info("property-id-start: " . $this->propertyId);
        try {
            $property = Property::find($this->propertyId);
            if ($property) {
                view()->share(['property' => $property]);
                $pdf = PDF::loadView('pdf.propertyBrochure');
                $pdfContent = $pdf->output();

                $saleOffer = PDF::loadView('pdf.propertySaleOffer');
                $saleOfferPdf = $saleOffer->output();
                //return $saleOfferPdf->stream();

                $property->clearMediaCollection('brochures');
                $property->clearMediaCollection('saleOffers');


                $property->addMediaFromString($pdfContent)
                    ->usingFileName($property->name . '-brochure.pdf')
                    ->toMediaCollection('brochures', 'propertyFiles');

                $property->addMediaFromString($saleOfferPdf)
                    ->usingFileName($property->name . '-saleoffer.pdf')
                    ->toMediaCollection('saleOffers', 'propertyFiles');

                $property->save();
                $property->updated_brochure = 1;
                $property->save();

                DB::commit();
            }
            Log::info("Property SaleOffer has been updated successfully.");
        } catch (\Exception $error) {
            Log::info("Property-error" . $error->getMessage());
        }
        Log::info("property-id-end: " . $this->propertyId);
        Log::info("StorePropertySaleOffer-end-time: " . Carbon::now());
    }
}
