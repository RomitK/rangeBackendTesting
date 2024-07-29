<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\{
    Accommodation,
    Agent,
    Amenity,
    Category,
    Community,
    CompletionStatus,
    Developer,
    Imagegallery,
    OfferType,
    Property,
    PropertyAmenity,
    PropertyGallery,
    Subcommunity,
    Project
};
use Illuminate\Http\File;
use App\Jobs\XMLSubImageJob;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\DB;
use PDF;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class CronController extends Controller
{
    public function propertiesPermitNumber()
    {
        DB::beginTransaction();
        try {
            $projects = Project::skip(140)->take(40)->orderBy('id', 'asc')->get();
           
            foreach($projects as $project){
                $properties = Property::whereIn('project_id', [$project->id])->get();
                
                foreach($properties as $property){
                    Log::info("projectID-".$project->id."propertyID-".$property->id);
                    echo "projectID-".$project->id."propertyID-".$property->id;
                    Property::getModel()->timestamps = false;
                    $property->permit_number = $project->permit_number;
                    $property->save();
                    if($project->qr_link){
                        $property->addMediaFromUrl($project->qr_link)->toMediaCollection('qrs', 'propertyFiles' );
                    }
                    $property->save();

                    if (!empty($property->permit_number) && !empty($property->qr_link)) {
                        $property->is_valid = 1;
                    } else {
                        $property->is_valid = 0; // Optionally set to false if not valid
                    }
                    $property->save();

                    Property::getModel()->timestamps = true;
                }
            }
            DB::commit();
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()]);
        }
    }
    public function getRentListings()
    {

        $feed = 'https://webapi.goyzer.com/Company.asmx/RentListings?AccessCode='.env('API_ACCESS_CODE').'&GroupCode='.env('API_GROUP_CODE').'&PropertyType=&Bedrooms=&StartPriceRange=&EndPriceRange=&categoryID=&CountryID=&StateID=&CommunityID=&FloorAreaMin=&FloorAreaMax=&UnitCategory=&UnitID=&BedroomsMax=&PropertyID=&ReadyNow=&PageIndex=';
        $xml_arr  = simplexml_load_file($feed,'SimpleXMLElement',LIBXML_NOCDATA);
        
        $xml_arr  = json_decode(json_encode($xml_arr,true),true);
        dd($xml_arr);

        $baseUrl = 'https://webapi.goyzer.com';
        $endpoint = '/Company.asmx/RentListings';

        $queryParams = [
            'AccessCode' => env('API_ACCESS_CODE'),
            'GroupCode' => env('API_GROUP_CODE'),
            'PropertyType' => '',
            'Bedrooms' => '',
            'StartPriceRange' => '',
            'EndPriceRange' => '',
            'categoryID' => '',
            'CountryID' => '',
            'StateID' => '',
            'CommunityID' => '',
            'FloorAreaMin' => '',
            'FloorAreaMax' => '',
            'UnitCategory' => '',
            'UnitID' => '',
            'BedroomsMax' => '',
            'PropertyID' => '',
            'ReadyNow' => '',
            'PageIndex' => '',
        ];

        $response = Http::get($baseUrl . $endpoint, $queryParams);

        if ($response->successful()) {

             // Raw response body
    $body = $response->body();
    // JSON decoded response
    $data = $body->json();
    // Response headers
    $headers = $response->headers();
    
    dd( $data);
          //  dd($response->body());
            return $response->json();
        } else {
            return response()->json(['error' => 'Unable to fetch data'], 500);
        }
    }
    public function deleteNAProperties()
    {
        Log::info('deleteNAProperties');
        $projects = Project::mainProject()->pluck('id')->toArray();
        foreach ($projects as $project) {
            $subprojects = Project::where('is_parent_project', 0)->where('parent_project_id', $project)->pluck('id')->toArray();
            foreach ($subprojects  as $subproject) {
                Log::info('project_id' . $project);
                Log::info('sub_project_id' . $subproject);

                $lowestPricePropertyId = Property::where('project_id', $project)
                    ->where('sub_project_id', $subproject)
                    ->orderBy('price')
                    // ->active()
                    // ->approved()
                    ->value('id');

                Log::info("lowest priece property-" . $lowestPricePropertyId);

                Property::getModel()->timestamps = false;

                Property::where('project_id', $project)
                    ->where('sub_project_id', $subproject)
                    ->where('id', '!=', $lowestPricePropertyId)
                    ->update(['is_duplicate' => 1]);

                Property::getModel()->timestamps = true;

                Log::info("other lowest priece property-");
            }
        }
        echo "deleteNAProperties Done";
    }
    public function activeProperties()
    {

        Log::info('activeProperties Start-' . Carbon::now());
        DB::beginTransaction();
        try {

            $projects = Project::mainProject()->where('website_status', config('constants.available'))->latest()->get();


            $properties = Property::with('project')->latest()->get();

            foreach ($projects as $project) {
                Property::getModel()->timestamps = false;

                Property::where('project_id', $project->id)
                    ->where('status', config('constants.inactive'))
                    ->where('is_approved', config('constants.approved'))
                    ->where('website_status', config('constants.NA'))
                    ->update(['status' => config('constants.active'), 'website_status' => config('constants.available'),]);

                Property::getModel()->timestamps = TRUE; // Disable timestamps

            }

            DB::commit();
            Log::info('activeProperties End-' . Carbon::now());
            echo  "properties done";
        } catch (\Exception $error) {
            echo  $error->getMessage();
        }
    }
    public function NAProperties()
    {
        $properties = Property::where('status', config('constants.active'))
            ->where('website_status', config('constants.available'))
            ->whereHas('project', function ($query) {
                $query->where('qr_link',  '')->whereNull('qr_link')
                    ->whereNull('permit_number');
            })->get();
        // dd($properties);
        foreach ($properties as $property) {
            Property::getModel()->timestamps = false;
            Log::info('property-' . $property->id);
            Property::where('id', $property->id)
                ->update(['status' => config('constants.Inactive'), 'website_status' => config('constants.NA')]);

            Property::getModel()->timestamps = TRUE; // Disable timestamps

        }
    }
    public function inactiveProperties()
    {
        Log::info('inactiveProperties');
        $projects = Project::mainProject()->pluck('id')->toArray();
        foreach ($projects as $project) {
            $subprojects = Project::where('is_parent_project', 0)->where('parent_project_id', $project)->pluck('id')->toArray();
            foreach ($subprojects  as $subproject) {
                Log::info('project_id' . $project);
                Log::info('sub_project_id' . $subproject);


                $lowestPricePropertyId = Property::where('project_id', $project)
                    ->where('sub_project_id', $subproject)
                    ->orderBy('price')
                    ->active()
                    ->approved()
                    ->value('id');

                Log::info("lowest priece property-" . $lowestPricePropertyId);


                // DB::table('properties')
                //     ->where('project_id', $project)
                //     ->where('sub_project_id', $subproject)
                //     ->where('status', config('constants.inactive'))
                //     ->where('is_approved', config('constants.approved'))
                //     ->where('id', '!=', $lowestPricePropertyId)
                //     ->delete();

                Property::getModel()->timestamps = false;

                Property::where('project_id', $project)
                    ->where('sub_project_id', $subproject)
                    ->where('status', config('constants.active'))
                    ->where('is_approved', config('constants.approved'))
                    ->where('id', '!=', $lowestPricePropertyId)

                    ->update(['status' => config('constants.Inactive')]);

                Property::getModel()->timestamps = true;
                Log::info("other lowest priece property-");
            }
        }
        echo "done";
    }


    public function subProjects()
    {
        DB::beginTransaction();
        try {
            $projects = Project::active()->approved()->get();
            foreach ($projects as $project) {
                Log::info("projectId" . $project->id);
                $subProjects = $project->subProjects()->active()->where('is_approved', 'requested')->pluck('id')->toArray();
                Project::whereIn('id', $subProjects)->update([
                    'approval_id' =>  $project->approval_id,
                    'is_approved' => $project->is_approved,
                    'updated_by' => $project->updated_by
                ]);
            }
            echo  "project sub project done";
            DB::commit();
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()]);
        }
    }


    public function makeRequest()
    {
        $url = 'https://demo-ipg.ctdev.comtrust.ae:2443';

        $data = [
            'Registration' => [
                'Currency' => 'AED',
                'ReturnPath' => 'https://localhost/callbackURL',
                'TransactionHint' => 'CPT:Y;VCC:Y;',
                'OrderID' => '7210055701315195',
                'Store' => '0000',
                'Terminal' => '0000',
                'Channel' => 'Web',
                'Amount' => '2.00',
                'Customer' => 'Demo Merchant',
                'OrderName' => 'Paybill',
                'UserName' => 'Demo_fY9c',
                'Password' => 'Comtrust@20182018',
            ],
        ];

        $client = new Client();

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody(), true);

            // Process the result as needed
            return response()->json($result, $statusCode);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // need to rerun the cronjob for the first 250 property as main images dont want watermark(2 feb 2024)

    public function propertyBanner()
    {
        Log::info('propertyBannner Start-' . Carbon::now());
        DB::beginTransaction();
        try {
            // 520-580
            $properties = Property::orderBy('id', 'asc')->skip(700)->take(50)->get();

            foreach ($properties as $property) {
                Log::info('projectId-' . $property->id . "reference-number: " . $property->reference_number);


                // $lastMediaItem = $property->getMedia('mainImages')->first();

                // if($lastMediaItem && url_exists($lastMediaItem->getUrl())){

                //     $property->addMediaFromUrl($lastMediaItem->getUrl())->toMediaCollection('mainImages', 'propertyFiles' );

                // }

                // if($lastMediaItem && Media::where('id',  $lastMediaItem->id)->exists()){

                //     $media = Media::where('id',  $lastMediaItem->id)->first();
                //     $media->delete();
                // }


                $property->property_banner = $property->mainImage;
                $property->save();
            }
            DB::commit();
            Log::info('propertyBanner End-' . Carbon::now());
            echo  "property done";
        } catch (\Exception $error) {
            echo  $error->getMessage();
        }
    }
    public function propertyUpdate()
    {
        Log::info('propertyUpdate Start-' . Carbon::now());
        DB::beginTransaction();
        try {
            // 367-370
            $properties = Property::orderBy('id', 'asc')->skip(663)->take(3)->get();

            foreach ($properties as $property) {

                $oldsubImageIds = $property->getMedia('subImages')->pluck('id');

                Log::info('property-' . $property->id . "reference-number: " . $property->reference_number);


                // $lastMediaItem = $property->getMedia('mainImages')->first();

                // if($lastMediaItem && url_exists($lastMediaItem->getUrl())){

                //     $property->addMediaFromUrl($lastMediaItem->getUrl())->toMediaCollection('mainImages', 'propertyFiles' );

                // }

                foreach ($property->getMedia('subImages') as $media) {
                    $id = $media->id;

                    $property->addMediaFromUrl($media->getUrl())
                        ->withCustomProperties([
                            'title' => $property->name,
                            'order' => $media->getCustomProperty('order')
                        ])->toMediaCollection('subImages', 'propertyFiles');
                }
                if (count($oldsubImageIds) > 0) {

                    Media::whereIn('id',  $oldsubImageIds)->delete();
                }

                $property->save();
                $property->updated_brochure = 1;

                //$lastImage = $property->getMedia('mainImages')->last();

                // if ($lastImage) {
                //     $lastImageUrl = $lastImage->getUrl('resize');
                //     $property->property_banner = $lastImageUrl;
                //     Log::info($lastImageUrl);
                // }


                // $property->save();


                view()->share(['property' => $property]);

                $pdf = PDF::loadView('pdf.propertyBrochure');
                $pdfContent = $pdf->output();

                // $saleOffer = PDF::loadView('pdf.propertySaleOffer');
                // $saleOfferPdf = $saleOffer->output();


                $property->clearMediaCollection('brochures');
                // $property->clearMediaCollection('saleOffers');


                $property->addMediaFromString($pdfContent)
                    ->usingFileName($property->name . '-brochure.pdf')
                    ->toMediaCollection('brochures', 'propertyFiles');

                // $property->addMediaFromString($saleOfferPdf)
                //          ->usingFileName($property->name.'-saleoffer.pdf')
                //          ->toMediaCollection('saleOffers', 'propertyFiles' );

                $property->save();

                // if($lastMediaItem && Media::where('id',  $lastMediaItem->id)->exists()){

                //     $media = Media::where('id',  $lastMediaItem->id)->first();
                //     $media->delete();
                // }


            }
            DB::commit();
            Log::info('propertyUpdate End-' . Carbon::now());
            echo  "property done";
        } catch (\Exception $error) {
            echo  $error->getMessage();
        }
    }
    public function projectBrochure()
    {
        Log::info('projectBrochure Start-' . Carbon::now());
        DB::beginTransaction();
        try {
            // 1- 70
            $projects = Project::mainProject()->active()->approved()->orderBy('id', 'asc')->skip(5)->take(5)->get();

            foreach ($projects as $project) {
                Log::info('projectId-' . $project->id . "reference-number" . $project->reference_number);


                $minBed = $project->subProjects->min('bedrooms');
                $maxBed = $project->subProjects->max('bedrooms');
                if ($minBed != $maxBed) {
                    if ($maxBed === "Studio") {
                        $bedroom = $maxBed . "-" . $minBed;
                    } else {
                        $bedroom = $minBed . "-" . $maxBed;
                    }
                } else {
                    $bedroom = $minBed;
                }
                $area_unit = 'sq ft';

                $starting_price = 0;
                $dateStr = $project->completion_date;
                $month = date("n", strtotime($dateStr));
                $yearQuarter = ceil($month / 3);

                view()->share([
                    'project' => $project,
                    'area_unit' => $area_unit,
                    'starting_price' => count($project->subProjects) > 0 ? $project->subProjects->where('starting_price', $project->subProjects->min('starting_price'))->first()->starting_price : 0,
                    'bedrooms' => $bedroom,
                    'handOver' => "Q" . $yearQuarter . " " . date("Y", strtotime($dateStr)),
                    'communityName' => $project->mainCommunity ? $project->mainCommunity->name : '',

                ]);
                $pdf = PDF::loadView('pdf.projectBrochure');
                $pdfContent = $pdf->output();

                $project->clearMediaCollection('brochures');
                $project->addMediaFromString($pdfContent)
                    ->usingFileName($project->title . '-brochure.pdf')
                    ->toMediaCollection('brochures', 'projectFiles');

                $project->save();
                $project->brochure_link = $project->brochure;
                $project->updated_brochure = 1;
                $project->save();
            }
            DB::commit();
            Log::info('projectBrochure End-' . Carbon::now());
            echo  "project done";
        } catch (\Exception $error) {
            echo  $error->getMessage();
        }
    }
    private function applyWatermark($mediaItem)
    {

        $tempPath = tempnam(sys_get_temp_dir(), 'media') . '.' . $mediaItem->extension;
        $watermarkPath = public_path('path_to_watermark.png'); // Replace with your watermark image path

        // Download the image from S3 to a temporary file
        copy($mediaItem->getUrl(), $tempPath);

        // Apply the watermark
        Image::load($tempPath)
            ->watermark($watermarkPath)
            ->watermarkPosition(Manipulations::POSITION_CENTER)
            ->watermarkHeight(50, Manipulations::UNIT_PERCENT)
            ->watermarkOpacity(60)
            ->save();

        // Replace the original media item with the watermarked image
        $mediaItem->update([
            'file_name' => basename($tempPath)
        ]);

        // Upload the watermarked image back to S3
        $mediaItem->toMediaCollection('mainImages', 's3');

        // Clean up the temporary file
        unlink($tempPath);
    }

    public function propertyWaterMark()
    {
        Log::info('propertyWaterMark Start-' . Carbon::now());
        DB::beginTransaction();

        try {
            $properties = Property::orderBy('id', 'asc')->take(4)->get();
            foreach ($properties as $property) {
                Log::info('propertyId-' . $property->id);
                $lastMediaItem = $property->getMedia('mainImages')->last();

                if ($lastMediaItem && url_exists($lastMediaItem->getUrl())) {

                    // Add new media from URL and get the media instance
                    //  $newMedia = $property->addMediaFromUrl($lastMediaItem->getUrl())->toMediaCollection('mainImages', 'propertyFiles');
                    count($property->getMedia('subImages'));

                    foreach ($property->getMedia('subImages') as $media) {
                        $id = $media->id;

                        $property->addMediaFromUrl($media->getUrl())
                            ->withCustomProperties([
                                'title' => $property->name,
                                'order' => $media->getCustomProperty('order')
                            ])->toMediaCollection('subImages', 'propertyFiles');
                    }



                    $property->save();
                    DB::commit();


                    //$property->property_banner = $newMedia->getUrl('resize');
                    //$property->save();

                    //$medias = $property->getMedia('mainImages')->sortByDesc('created_at');

                    // Check if there are more than one media items
                    // if ($medias->count() > 1) {
                    //     // Exclude the first item (newest) and delete the rest
                    //     $medias->skip(1)->each(function ($media) {
                    //         $media->delete();
                    //     });
                    // }

                    Log::info('propertyId-' . $property->id);
                }
            }
            Log::info('propertyWaterMark End-' . Carbon::now());
        } catch (\Exception $error) {
            echo  $error->getMessage();
        }
    }
    public function addxml()
    {
        $apiURL     = 'https://xml.propspace.com/feed/xml.php?cl=1982&pid=8245&acc=8807';

        $xml_arr  = simplexml_load_file($apiURL, 'SimpleXMLElement', LIBXML_NOCDATA);

        $xml_arr  = json_decode(json_encode($xml_arr, true), true);


        $propertAll = Property::where('reference_number', '!=', NULL)->where('property_source', 'xml')->get();

        foreach ($propertAll as $prop) {
            $flag = 0;
            foreach ($xml_arr['Listing'] as $key => $value) {
                if ($prop['reference_number'] == $value['Property_Ref_No']) {
                    $flag = 1;
                    break;
                } else {
                    $flag = 0;
                }
            }
            if ($flag == 0) {
                $propDel = Property::where('id', '=', $prop['id'])->first();
                $propDel->delete();
            }
        }

        foreach ($xml_arr['Listing'] as $key => $value) {

            $allraedy               = Property::where('reference_number', $value['Property_Ref_No'])->first();

            $property               = $allraedy ? $allraedy : new Property;

            $property->reference_number     = array_key_exists("Property_Ref_No", $value) ? $value['Property_Ref_No'] : '';

            $property->unit_refNo     = array_key_exists("Unit_Reference_No", $value) ? $value['Unit_Reference_No'] : '';

            $property->permit_number     = array_key_exists("permit_number", $value) ? $value['permit_number'] : '';
            $property->sub_title     = array_key_exists("Property_Name", $value) ? $value['Property_Name'] : '';
            $property->name     = array_key_exists("Property_Title", $value) ? $value['Property_Title'] : '';

            // $property->community_id = 1;

            // $property->category_id = 1;
            // $property->user_id = 1;

            $property->description     = array_key_exists("Web_Remarks", $value) ? $value['Web_Remarks'] : '';
            $property->property_banner     = array_key_exists("Images", $value) ? (count($value['Images']['image']) > 0 ? $value['Images']['image']['0'] : '') : '';


            $property->bedrooms     = array_key_exists("Bedrooms", $value) ? (!empty($value['Bedrooms']) ? $value['Bedrooms'] : 0) : 0;
            $property->bathrooms     = array_key_exists("No_of_Bathroom", $value) ? (!empty($value['No_of_Bathroom']) ? $value['No_of_Bathroom'] : 0) : 0;
            $property->parking_space     = array_key_exists("Parking", $value) ? (!empty($value['Parking']) ? $value['Parking'] : 0) : 0;
            $property->furnished     = '';

            $property->area     = array_key_exists("Unit_Builtup_Area", $value) ? (!empty($value['Unit_Builtup_Area']) ? $value['Unit_Builtup_Area'] : 0) : 0;

            $property->unit_measure     = array_key_exists("unit_measure", $value) ? (!empty($value['unit_measure']) ? $value['unit_measure'] : 'Sq.Ft') : 'Sq.Ft';

            $property->price     = array_key_exists("Price", $value) ? (!empty($value['Price']) ? $value['Price'] : '') : '';
            $property->currency     = 'AED';

            $property->cheque_frequency     = array_key_exists("Frequency", $value) ? (!empty($value['Frequency']) ? $value['Frequency'] : '') : '';

            $property->address     = array_key_exists("Community", $value) ? (!empty($value['Community']) ? $value['Community'] : '') : '';


            $property->exclusive     = array_key_exists("Exclusive", $value) ? (!empty($value['Exclusive']) ? ($value['Exclusive']) : '0') : '0';

            $property->address_latitude     = array_key_exists("Latitude", $value) ? (!empty($value['Latitude']) ? $value['Latitude'] : '') : '';
            $property->address_longitude     = array_key_exists("Longitude", $value) ? (!empty($value['Longitude']) ? $value['Longitude'] : '') : '';

            // $projectExists = Project::where(['address_longitude'=>$property->address_latitude, 'address_latitude'=>$property->address_longitude])->exists();
            // if($projectExists){
            //     $projectExists = Project::where(['address_longitude'=>$property->address_latitude, 'address_latitude'=>$property->address_longitude])->first();
            //     $property->project_id =  $projectExists->id;
            // }

            $property->emirate     = array_key_exists("Emirate", $value) ? (!empty($value['Emirate']) ? $value['Emirate'] . ', ' : '') : '';
            $property->primary_view     = array_key_exists("Primary_View", $value) ? (!empty($value['Primary_View']) ? $value['Primary_View'] : '') : '';

            $property->property_source     = 'xml';

            $property->status     = config('constants.active');

            $property->rating     = 5;
            $property->user_id     = 1;


            // if(is_array($value['completion_status']) || is_object($value['completion_status'])) { 
            //     if(in_array('off_plan', $value['completion_status'])){
            //       $property->category_id   = '2';

            //     }else{
            //       if($value['Ad_Type'] == 'Sale'){
            //             $property->category_id   = '4'; 
            //       }else if($value['Ad_Type'] == 'Rent'){
            //             $property->category_id   = '1'; 
            //       } 
            //     }  
            // }else{
            //     if($value['completion_status']=='off_plan'){
            //       $property->status   = '2';
            //     }else{
            //       if($value['Ad_Type'] == 'Sale'){
            //             $property->category_id   = '4'; 
            //       }else if($value['Ad_Type'] == 'Rent'){
            //             $property->category_id   = '1'; 
            //       }

            //     }
            // }

            $staCode = array_key_exists("Ad_Type", $value) ? (!empty($value['Ad_Type']) ? $value['Ad_Type'] : '') : '';
            if ($staCode != '') {
                if ($staCode == "Sale" || $staCode == "sale") {
                    $staCode = "Buy";
                }
                $cat = Category::where('name', 'like', '%' . $staCode . '%')->first();
                if (!empty($cat)) {
                    $property->category()->associate($cat->id);
                } else {
                    $catgry = new Category;
                    $catgry->name = $staCode;
                    $catgry->status = config('constants.active');
                    $catgry->user_id = 1;
                    $catgry->save();
                    $property->category()->associate($catgry->id);
                }
            }

            $comName = array_key_exists("Community", $value) ? (!empty($value['Community']) ? $value['Community'] : '') : '';

            if ($comName != '') {
                $community = Community::where('name', 'like', '%' . $comName . '%')->first();
                if (!empty($community)) {
                    $property->communities()->associate($community->id);
                } else {
                    $community = new Community();
                    $community->name = $comName;
                    $community->emirates = array_key_exists("Emirate", $value) ? (!empty($value['Emirate']) ? $value['Emirate'] : '') : '';
                    $community->status = config('constants.Inactive');
                    $community->user_id = 1;
                    $community->save();
                    $property->communities()->associate($community->id);
                }
            }



            // $offerType = array_key_exists("Unit_Type", $value) ? (!empty($value['Unit_Type']) ? $value['Unit_Type'] : '') : '';
            // if ($offerType != '') {
            //     $offerName = explode(' ', trim($offerType))[0];

            //     $offType = OfferType::where('name', 'like', '%' . $offerName . '%')->first();
            //     if (!empty($offType)) {
            //         $property->offerType()->associate($offType->id);
            //     } else {
            //         $typeOffer = new OfferType();
            //         $typeOffer->name = $offerName;
            //         $typeOffer->status = config('constants.active');
            //         $typeOffer->user_id = 1;
            //         $typeOffer->save();
            //         $property->offerType()->associate($typeOffer->id);
            //     }
            // }

            // $subComName = array_key_exists("custom_fields", $value) ? (!empty($value['custom_fields']['pba_uaefields__sub_community_propertyfinder']) ? $value['custom_fields']['pba_uaefields__sub_community_propertyfinder'] : '') : '';
            // if ($subComName != '') {
            //     $subCommunity = Subcommunity::where('name', 'like', '%' . $subComName . '%')->where('community_id', $property->community_id)->first();
            //     if (!empty($subCommunity)) {
            //         $property->subcommunities()->associate($subCommunity->id);
            //     } else {
            //         $subComnty = new Subcommunity();
            //         $subComnty->name = $subComName;
            //         $subComnty->community_id = $property->community_id;
            //         $subComnty->status = config('constants.active');
            //         $subComnty->user_id = 1;
            //         $subComnty->save();
            //         $property->subcommunities()->associate($subComnty->id);
            //     }
            // }

            $propAccom = array_key_exists("Unit_Type", $value) ? (!empty($value['Unit_Type']) ? $value['Unit_Type'] : '') : '';
            if ($propAccom != '') {
                $propTyp = Accommodation::where('name', 'like', '%' . $propAccom . '%')->first();
                $acc = $propTyp ? $propTyp : new Accommodation;
                $acc->name = $propAccom;
                $acc->status = config('constants.active');
                $acc->user_id = 1;
                $acc->save();
                $property->accommodations()->associate($acc->id);
            }

            if (array_key_exists("Listing_Agent_Email", $value)) {
                $existsuser = Agent::where('email', $value['Listing_Agent_Email'])->first();

                $users = $existsuser ? $existsuser : new Agent;
                $users->name = (isset($value['Listing_Agent']) ? $value['Listing_Agent'] : '');
                $users->email = isset($value['Listing_Agent_Email']) ? $value['Listing_Agent_Email'] : '';
                $users->whatsapp_number = isset($value['Listing_Agent_Phone']) ? $value['Listing_Agent_Phone'] : '';
                $users->contact_number = isset($value['Listing_Agent_Phone']) ? $value['Listing_Agent_Phone'] : '';
                $users->status = config('constants.Inactive');
                $users->user_id = 1;
                $users->save();
                $property->agent()->associate($users->id);
            }

            $compStatus = array_key_exists("completion_status", $value) ? (!empty($value['completion_status']) ? $value['completion_status'] : '') : '';

            if ($compStatus != '') {
                $existcompl = CompletionStatus::where('xml_name', 'like', '%' . $compStatus . '%')->first();
                if (!empty($existcompl)) {
                    $property->completionStatus()->associate($existcompl->id);
                } else {
                    $existcomplStats =  new CompletionStatus;
                    $existcomplStats->xml_name = $compStatus;
                    $existcomplStats->status = config('constants.active');
                    $existcomplStats->user_id = 1;
                    $existcomplStats->save();

                    $property->completionStatus()->associate($existcomplStats->id);
                }
            }

            $property->save();
            // $community = Community::where('id', $property->community_id )->first();
            if (Project::where('title', $property->sub_title)->exists()) {
                $project = Project::where('title', $property->sub_title)->first();
                $project->community_id = $community->id;
                $project->save();
            } else {
                $project = new Project();
                $project->title = $property->sub_title;
                $project->user_id = 1;
                $project->is_parent_project = 1;
                $project->project_source = 'xml';

                $project->address_latitude     = array_key_exists("Latitude", $value) ? (!empty($value['Latitude']) ? $value['Latitude'] : '') : '';
                $project->address_longitude     = array_key_exists("Longitude", $value) ? (!empty($value['Longitude']) ? $value['Longitude'] : '') : '';


                $project->community_id = $community->id;
                $project->save();
            }

            $property->project_id =  $project->id;
            $property->search_keyword = $property->name . ", " . $property->sub_title . "(" . $property->emirate . $community->name . ")";
            $property->save();
            if (array_key_exists("Facilities", $value) && (count($value['Facilities']['facility']) > 0)) {
                foreach ($value['Facilities']['facility'] as $keys => $faci) {
                    $checkFC = Amenity::where('name', $faci)->first();
                    $facility  = $checkFC ? $checkFC : new Amenity();
                    $facility->name   = $faci;
                    $facility->status   = config('constants.active');
                    $facility->user_id   = 1;
                    $facility->save();
                    $facCheck = PropertyAmenity::where('property_id', $property->id)->where('amenity_id', $facility->id)->first();
                    if ($facCheck) {
                    } else {
                        $property->amenities()->attach($facility->id);
                        // $propertyAmn = new PropertyAmenity;
                        // $propertyAmn->property_id = $property->id;
                        // $propertyAmn->amenity_id  = $facility->id;
                        // $propertyAmn->save();
                    }
                }
            }
            if (array_key_exists("Images", $value) && (count($value['Images']['image']) > 0)) {
                foreach ($value['Images']['image'] as $keys => $img) {
                    $checkGM = PropertyGallery::where('property_id', $property->id)->where('galleryimage', $img)->first();
                    $gallery                = $checkGM ? $checkGM : new PropertyGallery;
                    $gallery->property_id   = $property->id;
                    $gallery->galleryimage  = $img;
                    $gallery->save();
                }
            }
        }
        echo "Property added successfully.";
    }
    public function addxmlMainImg()
    {
        ini_set('max_execution_time', 6000);
        set_time_limit(6000);
        $apiURL     = 'https://manda.propertybase.com/api/v2/feed/00D4J000000qB4kUAE/XML2U/a0L4J0000008Hk4UAE/full';

        $xml_arr  = simplexml_load_file($apiURL, 'SimpleXMLElement', LIBXML_NOCDATA);

        $xml_arr  = json_decode(json_encode($xml_arr, true), true);
        foreach ($xml_arr['listing'] as $key => $value) {

            $allraedy               = Property::where('reference_number', $value['id'])->first();


            $property = $allraedy ? $allraedy : new Property;

            $img = array_key_exists("listing_media", $value) ? (!empty($value['listing_media']['images']) ? $value['listing_media']['images']['image']['0']['url'] : '') : '';
            if ($allraedy) {
                $property->clearMediaCollection('mainImages');
            }
            try {
                $property->addMediaFromUrl($img)->toMediaCollection('mainImages', 'propertyFiles');
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        echo "Property Image added successfully.";
    }
    public function addxmlSubImg()
    {
        // XMLSubImageJob::dispatch();

        ini_set('max_execution_time', 6000);
        set_time_limit(6000);
        $apiURL     = 'https://manda.propertybase.com/api/v2/feed/00D4J000000qB4kUAE/XML2U/a0L4J0000008Hk4UAE/full';

        $xml_arr  = simplexml_load_file($apiURL, 'SimpleXMLElement', LIBXML_NOCDATA);

        $xml_arr  = json_decode(json_encode($xml_arr, true), true);
        foreach ($xml_arr['listing'] as $key => $value) {

            $allraedy               = Property::where('reference_number', $value['id'])->first();

            $property               = $allraedy ? $allraedy : new Property;
            if ($allraedy) {
                $property->clearMediaCollection('subImages');
            }

            if (array_key_exists("listing_media", $value) && (count($value['listing_media']['images']['image']) > 0)) {
                foreach ($value['listing_media']['images']['image'] as $keys => $img) {
                    // if(filesize($img['url']) < (128 * 1024)){
                    if ($keys < 5) {
                        $property->addMediaFromUrl($img['url'])->toMediaCollection('subImages', 'propertyFiles');
                    } else {
                        break;
                    }
                    // }
                }
            }
        }
        echo "Property Sub Images added successfully.";
    }
    public function projectQR()
    {
        Log::info('projectQR Start-' . Carbon::now());
        DB::beginTransaction();
        try {
            // 1- 70
            $projects = Project::mainProject()->get();

            foreach ($projects as $project) {
                Log::info('projectId-' . $project->id);
                $project->timestamps = false; // Disable timestamps
                $project->update(['qr_link' => $project->qr]);
                $project->save();

                Log::info('projectQR-' . $project->qr_link);
            }
            DB::commit();
            Log::info('projectQR End-' . Carbon::now());
            echo  "project done";
        } catch (\Exception $error) {
            echo  $error->getMessage();
        }
    }
    public function cronJobmakeInctiveProperties()
    {
        Log::info('cronJobmakeInctiveProperties Start-' . Carbon::now());
        DB::beginTransaction();
        try {

            $properties = Property::with('project')->latest()->get();

            foreach ($properties as $property) {
                Log::info('propertyId-' . $property->id);
                if ($property->status == config('constants.active') && $property->is_approved == config('constants.approved')) {
                    if (is_null($property->project->permit_number) && $property->project->qr_link == '') {
                        $property->status = config('constants.Inactive');
                    }
                } elseif ($property->status == config('constants.Inactive') && $property->is_approved == config('constants.approved')) {
                    if (!is_null($property->project->permit_number) && $property->project->qr_link != '') {
                        $property->status = config('constants.active');
                    }
                }
                $property->timestamps = false; // Disable timestamps
                $property->save();
            }

            DB::commit();
            Log::info('cronJobmakeInctiveProperties End-' . Carbon::now());
            echo  "properties done";
        } catch (\Exception $error) {
            echo  $error->getMessage();
        }
    }
}
