<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
    Project,
    User
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

class GoyzerRentalProperties implements ShouldQueue
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
        Log::info('getRentListings');
        DB::beginTransaction();
        try{
            $today = Carbon::now();
            $user = User::where('email', 'goyzer@gmail.com')->first();
            $feed = 'https://webapi.goyzer.com/Company.asmx/RentListings?AccessCode='.env('API_ACCESS_CODE').'&GroupCode='.env('API_GROUP_CODE').'&PropertyType=&Bedrooms=&StartPriceRange=&EndPriceRange=&categoryID=&CountryID=&StateID=&CommunityID=&FloorAreaMin=&FloorAreaMax=&UnitCategory=&UnitID=&BedroomsMax=&PropertyID=&ReadyNow=&PageIndex=';
            $xml_arr  = simplexml_load_file($feed,'SimpleXMLElement',LIBXML_NOCDATA);        
            $xml_arr  = json_decode(json_encode($xml_arr,true),true);
    
            
            if (isset($xml_arr['UnitDTO']) && !empty($xml_arr['UnitDTO'])) {
                $properties = $xml_arr['UnitDTO'];


                $CRMProperties = Property::where('property_source', 'xml')->get();

                foreach ($CRMProperties as $prop) {
                    $flag = 0;
                    foreach ($properties as $key => $value) {
                        if ($prop['reference_number'] == $value['RefNo']) {
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

                foreach($properties as $rental){
                   
                   $qrCodeURL = NULL;

                    $RefNo = isset($rental['RefNo']) ? $rental['RefNo'] : '';
                    $communityName = isset($rental['Community']) ? $rental['Community'] : '';
                   
                    $accommodationName = isset($rental['Category']) ? $rental['Category'] : ''; 
                    $projectName = isset($rental['PropertyName']) ? $rental['PropertyName'] : ''; 
                    $BuiltupArea = isset($rental['BuiltupArea']) ? $rental['BuiltupArea'] : '';
                    $PrimaryUnitView = isset($rental['PrimaryUnitView']) ? $rental['PrimaryUnitView'] : '';
                    $SecondaryUnitView = isset($rental['SecondaryUnitView']) ? $rental['SecondaryUnitView'] : '';
                    $HandoverDate = isset($rental['HandoverDate']) ? $rental['HandoverDate'] : '';
                    if($HandoverDate){
                        $HandoverDate = date('Y-m-d', strtotime($HandoverDate));
                    }


                    $Agent = isset($rental['Agent']) ? $rental['Agent'] : '';
                    $ContactNumber = isset($rental['ContactNumber']) ? $rental['ContactNumber'] : '';
                    $StateName = isset($rental['StateName']) ? $rental['StateName'] : '';
                    $Remarks = isset($rental['Remarks']) ? $rental['Remarks'] : '';
                    $CountryName = isset($rental['CountryName']) ? $rental['CountryName'] : '';
                    $CityName = isset($rental['CityName']) ? $rental['CityName'] : '';
                    $DistrictName = isset($rental['DistrictName']) ? $rental['DistrictName'] : '';
                    $Rent = isset($rental['Rent']) ? $rental['Rent'] : '';
                    $ProGooglecoordinates = isset($rental['ProGooglecoordinates']) ? $rental['ProGooglecoordinates'] : '';
                    $SalesmanEmail = isset($rental['SalesmanEmail']) ? $rental['SalesmanEmail'] : '';
                    $MarketingTitle = isset($rental['MarketingTitle']) ? $rental['MarketingTitle'] : '';
                    $MarketingOptions = isset($rental['MarketingOptions']) ? $rental['MarketingOptions'] : '';
                    $Mandate = isset($rental['Mandate']) ? $rental['Mandate'] : '';
                    $RentPerMonth = isset($rental['RentPerMonth']) ? $rental['RentPerMonth'] : '';
                    $Rent = isset($rental['Rent']) ? $rental['Rent'] : '';
                    $ReraStrNo = isset($rental['ReraStrNo']) ? $rental['ReraStrNo'] : '';
                    $PermitNumber = isset($rental['PermitNumber']) ? $rental['PermitNumber'] : '';
                    $Images = isset($rental['Images']) ? $rental['Images'] : '';
                    $FittingFixtures = isset($rental['FittingFixtures']) ? $rental['FittingFixtures'] : '';
                    $Furnish_status = isset($rental['Furnish_status']) ? $rental['Furnish_status'] : '';
                    $Mandate = isset($rental['Mandate']) ? $rental['Mandate'] : '';
                    $Documents = isset($rental['Documents']) ? $rental['Documents'] : '';



                    if($Mandate == 'Exclusive'){
                        $exclusive = 1;
                    }else{
                        $exclusive = 0;
                    }
                    if($Furnish_status == 'Furnished'){
                        $is_furniture = 0;
                    }elseif($Furnish_status == "Unfurnished"){
                        $is_furniture = 1;
                    }else{
                        $is_furniture = 'partly';
                    }
                   
                    $NoOfBathrooms = isset($rental['NoOfBathrooms']) ? $rental['NoOfBathrooms'] : '';

                    if (is_array($NoOfBathrooms)) {
                        // If $Bedrooms is an array, take the first element
                        $NoOfBathrooms = !empty($NoOfBathrooms) ? $NoOfBathrooms[0] : '';
                    } elseif (is_string($NoOfBathrooms)) {
                        // If $Bedrooms is already a string, use it directly
                        $NoOfBathrooms = $NoOfBathrooms;
                    } else {
                        // Handle cases where $Bedrooms is neither an array nor a string
                        $NoOfBathrooms = '';
                    }

                    $Bedrooms = isset($rental['Bedrooms']) ? $rental['Bedrooms'] : '';

                    if (is_array($Bedrooms)) {
                        // If $Bedrooms is an array, take the first element
                        $Bedrooms = !empty($Bedrooms) ? $Bedrooms[0] : '';
                    } elseif (is_string($Bedrooms)) {
                        // If $Bedrooms is already a string, use it directly
                        $Bedrooms = $Bedrooms;
                    } else {
                        // Handle cases where $Bedrooms is neither an array nor a string
                        $Bedrooms = '';
                    }
                    
                    if (Agent::where('email', $SalesmanEmail)->orWhere('secondary_email', $SalesmanEmail)->exists()) {
                        // Fetch existing agent
                        $agentD = Agent::where('email', $SalesmanEmail)
                                       ->orWhere('secondary_email', $SalesmanEmail)
                                       ->first();
                    } else {
                        // Create a new agent
                        $agentD = new Agent;
                        $agentD->name = $Agent;
                        $agentD->email = $SalesmanEmail;
                        $agentD->status = 'Inactive';  // Correct variable name
                        $agentD->user_id = $user->id;
                        $agentD->save();
                    }
                   
                    
                    if(Accommodation::where('name', 'like', "%$accommodationName%")->exists()){
                        $propertyType = Accommodation::where('name', 'like', "%$accommodationName%")->first();
                    }else{
                        $propertyType = new Accommodation;
                        $propertyType->name = $accommodationName;
                        $propertyType->save();
                    }
                    if(Community::where('name', 'like', "%$communityName%")->exists()){

                        $community = Community::where('name', 'like', "%$communityName%")->first();

                    }else{
                        $community = new Community;
                        $community->name = $communityName;
                        $community->is_approved = config('constants.requested');
                        $community->status = config('constants.active');
                        $community->website_status = config('constants.requested');
                        $community->community_source = 'xml';

                        $community->address = $community->name. " ". $CityName. " ".$CountryName;

                        if($ProGooglecoordinates){
                            list($latitude, $longitude) = explode(',', $ProGooglecoordinates);
                            // Trim any extra spaces
                            $latitude = trim($latitude);
                            $longitude = trim($longitude);
                            $community->address_latitude = $latitude;
                            $community->address_longitude = $longitude;
                            
                        }

                        $community->user_id = $user->id;
                        $community->save();
                        
                    }
                   
                    if(Project::where('title', 'like', "%$projectName%")->where('is_parent_project', 1)->exists()){
                        $project = Project::where('title', 'like', "%$projectName%")->where('is_parent_project', 1)->first();
                    }else{
                        $project = new Project;
                      
                        $titleArray = explode(' ', $projectName);
                        $sub_title = $titleArray[0];

                        $subTitle1 = array_shift($titleArray);
                        $sub_title_1 = implode(" ",  $titleArray);
                        $project->title = $projectName;
                        $project->sub_title = $sub_title;
                        $project->sub_title_1 = $sub_title_1;
                        $project->is_approved = config('constants.requested');
                        $project->status = config('constants.active');
                        $project->website_status = config('constants.requested');
                        $project->project_source = 'xml';
                        $project->community_id = $community->id;
                        $project->accommodation_id = $propertyType->id;
                        $project->bathrooms = $Bedrooms;
                        $project->address = $community->name. " ". $CityName. " ".$CountryName;
                        //$project->completion_date = $HandoverDate;

                        if ($HandoverDate > $today) {
                            $project->completion_status_id = config('constants.underConstruction');
                        } else {
                            $project->completion_status_id = config('constants.completed');
                        }

                        if($ProGooglecoordinates){
                            list($latitude, $longitude) = explode(',', $ProGooglecoordinates);
                            // Trim any extra spaces
                            $latitude = trim($latitude);
                            $longitude = trim($longitude);
                            $project->address_latitude = $latitude;
                            $project->address_longitude = $longitude;
                        }
                        
                        $project->user_id = $user->id;
                        $project->is_parent_project = 1; 
                        $project->save();
                       
                    }
                    

                    
                    if(Property::where('reference_number', $RefNo)->exists()){
                        $property = Property::where('reference_number', $RefNo)->first();
                    }else{
                        $property = new Property();
                    }

                    $property->name = $MarketingTitle;
                    $property->used_for = $propertyType->type;
                    $property->sub_title = $MarketingTitle;
                   
                    $property->is_furniture = $is_furniture;
                    //$property->emirate = $request->emirate;
                    $property->permit_number = $PermitNumber;
                    $property->short_description = $Remarks;
                    $property->description = $Remarks;
                    $property->bathrooms = $NoOfBathrooms;
                    $property->bedrooms = $Bedrooms;
                    $property->area = $BuiltupArea;
                    $property->builtup_area = $BuiltupArea;
                    $property->is_luxury_property = $exclusive;
                    
                  
                    $property->price = $Rent;
                    //$property->rental_period = $request->rental_period;
                    $property->is_feature = 0;
                    $property->exclusive = $exclusive;
                    $property->property_source = 'xml';
                    $property->primary_view = $PrimaryUnitView;
                    $property->is_display_home = 1;
                    if($ProGooglecoordinates){
                        list($latitude, $longitude) = explode(',', $ProGooglecoordinates);
                      
                        $latitude = trim($latitude);
                        $longitude = trim($longitude);
                        $property->address_latitude = $latitude;
                        $property->address_longitude = $longitude;   
                    }
                    $property->new_reference_number = $RefNo;
                    $property->address = $community->name. " ". $CityName. " ".$CountryName;
                    $property->user_id = $user->id;
                    $property->agent_id = $agentD->id;
                
                    $property->completion_status_id = 286;
                    $property->community_id = $community->id;
                    $property->accommodation_id = $propertyType->id;
                    $property->project_id = $project->id;
                    $property->category_id = 8;
                    $property->save();
                    if ($property->category_id = 8) {
                        $prefix = 'S';
                    } else {
                        $prefix = 'R';
                    }
                
        
                    if (!empty($property->permit_number) && !empty($property->qr_link)) {
                        $property->is_valid = 1;
                    } else {
                        $property->is_valid = 0; // Optionally set to false if not valid
                    }

                    $property->save();
                    


                    // amenities code start
                    
                    if ($FittingFixtures && (count($FittingFixtures) > 0)) {
                      
                        
                        foreach ($FittingFixtures as $keys => $facilityArray) {

                            foreach($facilityArray as $faci){
                                $faci = $faci['Name'];
                                $checkFC = Amenity::where('name', $faci)->exists();
                                if($checkFC){
                                    $checkFC = Amenity::where('name', $faci)->first();
                                    $project->amenities()->attach($checkFC->id);
                                    $property->amenities()->attach($checkFC->id);
                                }else{

                                    $amenity = new Amenity;
                                    $amenity->name = $faci;
                                    $amenity->status = 'Inactive';
                                    $amenity->is_approved = config('constants.requested');
                                    $amenity->status = config('constants.Inactive');
                                    $amenity->user_id   = $user->id;
                                    
                                    $amenity->save();
                                    $project->amenities()->attach($amenity->id);
                                    $property->amenities()->attach($amenity->id);
                                }
                            }
                            
                            
                            
                        }
                    }
                    
                    // amenities code end
                   
                    if (isset($Images) && is_array($Images) && array_key_exists('Image', $Images)) {
                        
                       $Images = $Images['Image'];
                       //dd($Images);
                        // Take the first image as the main image
                        $mainImage = isset($Images[0]) ? $Images[0] : '';
                      
                        // Take the rest of the images as gallery images
                        $galleryImages = array_slice($rental['Images'], 1);
                    
                        // Output or use $mainImage and $galleryImages as needed
                        // For example:
                        // echo "Main Image: " . $mainImage;
                        // echo "Gallery Images: " . implode(', ', $galleryImages);
                    } else {
                        // Handle the case where 'Images' is not set or not an array
                        $mainImage = '';
                        $galleryImages = [];
                    }
                    if ($mainImage) {
                        $property->addMediaFromUrl($mainImage['ImageURL'])->toMediaCollection('mainImages', 'propertyFiles');
                    }
        
                    if ($galleryImages) {
                        foreach ($galleryImages as $key => $img) {

                            $property->addMediaFromUrl($img['ImageURL'])
                                    ->withCustomProperties([
                                        'title' =>$property->name,
                                        'order' => null
                                    ])
                                    ->toMediaCollection('subImages', 'propertyFiles');
                        }
                    }
                    if(isset($Documents) && is_array($Documents) && array_key_exists('Document', $Documents)){
                        $Document = $Documents['Document'];
                        foreach ($Document as $document) {

                            if ((string)$document->Title === 'QR Code') {
                                $qrCodeURL = $document->URL;
                                break; // Exit loop after finding the first QR Code document
                            }
                        }
                    }

                    if($qrCodeURL){
                        $property->addMediaFromUrl($qrCodeURL)->toMediaCollection('qrs', 'propertyFiles' );
                    }
                    $property->save();
                    $property->property_banner = $property->mainImage;
                    $property->qr_link = $property->qr;
                    $property->save();

                    if (!empty($property->permit_number) && !empty($property->qr_link)) {
                        $property->is_valid = 1;
                    } else {
                        $property->is_valid = 0; // Optionally set to false if not valid
                    }
                    $property->save();
                        
                    $originalAttributes = $property->getOriginal();

            $originalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
            $originalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));

            if ($request->has('amenityIds')) {
                $property->amenities()->attach($request->amenityIds);
                $originalAttributes['amenityIds'] = $request->amenityIds;
            } else {
                $originalAttributes['amenityIds'] = [];
            }

            // Log activity for developer creation
            $properties = json_encode([
                'old' => [],
                'new' => $originalAttributes,
                'updateAttribute' => [],
                'attribute' => []
            ]);
            logActivity('New Property has been created', $property->id, Property::class, $properties);


            if ($property->website_status == config('constants.available')) {
                $notValidProject = $property->where('is_valid', '!=', 1)->exists();

                if ($notValidProject) {

                    $property->status = config('constants.inactive');
                    $property->website_status = config('constants.NA');
                    $property->save();


                    $newPropertyOriginalAttributes = $property->getOriginal();

                    if ($property->amenities) {
                        $newPropertyOriginalAttributes['amenityIds'] = $property->amenities->pluck('id')->toArray();
                    } else {
                        $newPropertyOriginalAttributes['amenityIds'] = [];
                    }


                    if (isset($property->description)) {
                        $newPropertyOriginalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));
                    }
                    if (isset($property->short_description)) {
                        $newPropertyOriginalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
                    }

                    $properties = getUpdatedPropertiesForProperty($newPropertyOriginalAttributes, $originalAttributes);

                    logActivity('Property marked as NA due to missing to Permit Number and QR ', $property->id, Property::class, $properties);
                }
            }

                    Log::info("communityId=".$community->id);
                    Log::info("projectName=".$project->title);
                    Log::info("projectId=".$project->id);
                    Log::info("propertyId=".$property->id);
                    

                }
            }
            DB::commit();
            Log::info('getRentListings End-' . Carbon::now());
        }catch (\Exception $error) {
            $errorTrace = $error->getTraceAsString();
            $errorLine = $error->getLine();
            $errorFile = $error->getFile();
            
            $response = [
                'success' => false,
                'message' => $error->getMessage(),
                'error_trace' => $errorTrace,
                'error_file' => $errorFile,
                'error_line' => $errorLine,
            ];

            return response()->json($response, 500);
        }
    }
}
