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


class GoyzerSaleProperties implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // 1 hour

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
        Log::info('getSaleListings');
        DB::beginTransaction();
        try{
            $today = Carbon::now();
            $user = User::where('email', 'goyzer@gmail.com')->first();
            $userID = $user->id;

            //Log::info($userID);

            $feed = 'https://webapi.goyzer.com/Company.asmx/SalesListings?AccessCode=$R@nGe!NteRn@t!on@l&GroupCode=5084&Bedrooms=&StartPriceRange=&EndPriceRange=&categoryID=&SpecialProjects=&CountryID=&StateID=&CommunityID=&DistrictID=&FloorAreaMin=&FloorAreaMax=&UnitCategory=&UnitID=&BedroomsMax=&PropertyID=&ReadyNow=&PageIndex=';

           // $feed = 'https://webapi.goyzer.com/Company.asmx/SalesListings?AccessCode='.env('API_ACCESS_CODE').'&GroupCode='.env('API_GROUP_CODE').'&PropertyType=&Bedrooms=&StartPriceRange=&EndPriceRange=&categoryID=&CountryID=&StateID=&CommunityID=&FloorAreaMin=&FloorAreaMax=&UnitCategory=&UnitID=&BedroomsMax=&PropertyID=&ReadyNow=&PageIndex=';
            $xml_arr  = simplexml_load_file($feed,'SimpleXMLElement',LIBXML_NOCDATA);        
            $xml_arr  = json_decode(json_encode($xml_arr,true),true);
    
            
            if (isset($xml_arr['UnitDTO']) && !empty($xml_arr['UnitDTO'])) {
                $properties = $xml_arr['UnitDTO'];


                $CRMProperties = Property::where('property_source', 'xml')->where('category_id', 8)->get();

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
                $counter = 0;
                //$limitedProperties = array_slice($properties, 0, 50);

                foreach($properties as $index=>$rental){
                    // if ($counter >= 50) {
                    //     break;  // Exit the loop after processing 26 elements
                    // }
                    
                    //if($rental['RefNo'] == 'AP7466'){

                    Log::info($index);
                   $qrCodeURL = NULL;

                    $RefNo = isset($rental['RefNo']) ? $rental['RefNo'] : '';
                   
                    $communityName = isset($rental['Community']) ? $rental['Community'] : '';
                   
                    $accommodationName = isset($rental['Category']) ? $rental['Category'] : ''; 
                    $projectName = isset($rental['PropertyName']) ? $rental['PropertyName'] : ''; 
                    $BuiltupArea = isset($rental['BuiltupArea']) ? $rental['BuiltupArea'] : '';
                    $PrimaryUnitView = isset($rental['PrimaryUnitView']) 
                    ? (is_array($rental['PrimaryUnitView']) && empty($rental['PrimaryUnitView']) ? null : $rental['PrimaryUnitView'])
                    : '';

                    $SecondaryUnitView = isset($rental['SecondaryUnitView']) 
                    ? (is_array($rental['SecondaryUnitView']) && empty($rental['SecondaryUnitView']) ? null : $rental['SecondaryUnitView'])
                    : '';


                    $SecondaryUnitView = isset($rental['SecondaryUnitView']) ? $rental['SecondaryUnitView'] : '';
                    $HandoverDate = isset($rental['HandoverDate']) ? $rental['HandoverDate'] : '';
                    if($HandoverDate){
                        $HandoverDate = date('Y-m-d', strtotime($HandoverDate));
                    }
                    $Agent = isset($rental['Agent']) ? $rental['Agent'] : '';
                    $ContactNumber = isset($rental['ContactNumber']) ? $rental['ContactNumber'] : '';
                    $StateName = isset($rental['StateName']) ? $rental['StateName'] : '';
                    $Remarks = isset($rental['Remarks']) ? $rental['Remarks'] : '';
                    $Remarks = str_replace(['<![CDATA[', ']]>'], '', $Remarks);

                    $CountryName = isset($rental['CountryName']) ? $rental['CountryName'] : '';
                    $CityName = isset($rental['CityName']) ? $rental['CityName'] : '';
                    $DistrictName = isset($rental['DistrictName']) ? $rental['DistrictName'] : '';
                    $Rent = isset($rental['SellPrice']) ? $rental['SellPrice'] : '';
                    $ProGooglecoordinates = isset($rental['ProGooglecoordinates']) ? $rental['ProGooglecoordinates'] : '';
                    $SalesmanEmail = isset($rental['SalesmanEmail']) ? $rental['SalesmanEmail'] : '';
                    $MarketingTitle = isset($rental['MarketingTitle']) ? $rental['MarketingTitle'] : '';
                    $MarketingOptions = isset($rental['MarketingOptions']) ? $rental['MarketingOptions'] : '';
                    $Mandate = isset($rental['Mandate']) ? $rental['Mandate'] : '';
                    $RentPerMonth = isset($rental['RentPerMonth']) ? $rental['RentPerMonth'] : '';
                    $Rent = isset($rental['SellPrice']) ? $rental['SellPrice'] : '';
                    $ReraStrNo = isset($rental['ReraStrNo']) ? $rental['ReraStrNo'] : '';
                    //$PermitNumber = isset($rental['PermitNumber']) ? $rental['PermitNumber'] : '';

                    $PermitNumber = isset($rental['PermitNumber']) 
                    ? (is_array($rental['PermitNumber']) && empty($rental['PermitNumber']) ? null : $rental['PermitNumber'])
                    : '';


                    $Images = isset($rental['Images']) ? $rental['Images'] : '';
                    $FittingFixtures = isset($rental['FittingFixtures']) ? $rental['FittingFixtures'] : '';
                    $Furnish_status = isset($rental['Furnish_status']) ? $rental['Furnish_status'] : '';
                    $Mandate = isset($rental['Mandate']) ? $rental['Mandate'] : '';
                    $Documents = isset($rental['Documents']) ? $rental['Documents'] : '';

                    
                    if($ProGooglecoordinates){
                        list($longitude, $latitude) = explode(',', $ProGooglecoordinates);
                        $latitude = trim($latitude);
                        $longitude = trim($longitude);
                    }else{
                        $latitude = null;
                        $longitude = null;
                    }
                   

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
                    //$NoOfBathrooms = 2;
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
                    //$Bedrooms =2;
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
                    if($Bedrooms == 0 ){
                        $Bedrooms = 'Studio';
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
                        $agentD->user_id = $userID;
                        $agentD->save();
                    }
                   
                    
                    if(Accommodation::where('name', 'like', "%$accommodationName%")->exists()){
                        $propertyType = Accommodation::where('name', 'like', "%$accommodationName%")->first();
                    }else{
                        $propertyType = new Accommodation;
                        $propertyType->name = $accommodationName;
                        $propertyType->user_id = $userID;
                        $propertyType->save();
                    }
                    if(Community::where('name', 'like', "%$communityName%")->orWhere('name_1', 'like', "%$communityName%")->exists()){

                        $community = Community::where('name', 'like', "%$communityName%")->orWhere('name_1', 'like', "%$communityName%")->first();

                    }else{
                        $community = new Community;
                        $community->name = $communityName;
                        $community->is_approved = config('constants.requested');
                        $community->status = config('constants.active');
                        $community->website_status = config('constants.requested');
                        $community->community_source = 'xml';

                        $community->address = $community->name. " ". $CityName. " ".$CountryName;

                        if($ProGooglecoordinates){
                           
                            $community->address_latitude = $latitude;
                            $community->address_longitude = $longitude;
                            
                        }

                        $community->user_id = $userID;
                        $community->save();
                        
                    }

                    
                   
                    if(Project::where('title', 'like', "%$projectName%")->orWhere('title_1', 'like', "%$projectName%")->where('is_parent_project', 1)->exists()){
                        $project = Project::where('title', 'like', "%$projectName%")->orWhere('title_1', 'like', "%$projectName%")->where('is_parent_project', 1)->first();
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
                            $project->address_latitude = $latitude;
                            $project->address_longitude = $longitude;
                        }
                        
                        $project->user_id = $userID;
                        $project->is_parent_project = 1; 
                        $project->save();
                    }
                    $subProjectTypeExist =  Project::where('is_parent_project', 0)->where('parent_project_id', $project->id)->where('list_type', config('constants.secondary'))->exists(); 
                    if($subProjectTypeExist){
                        $subProjectTypeBedroomExist =  Project::where('is_parent_project', 0)
                                                        ->where('parent_project_id', $project->id)
                                                        ->where('list_type', config('constants.secondary'))
                                                        ->where('bedrooms', $Bedrooms)
                                                        ->exists();
                        
                        if($subProjectTypeBedroomExist){
                            $subProjectTypeBedroomAreaExist =  Project::where('is_parent_project', 0)
                                                        ->where('parent_project_id', $project->id)
                                                        ->where('list_type', config('constants.secondary'))
                                                        ->where('bedrooms', $Bedrooms)
                                                        ->where('area', $BuiltupArea)
                                                        ->exists();
                            if($subProjectTypeBedroomAreaExist){
                                
                                $subProject =  Project::where('is_parent_project', 0)
                                                ->where('parent_project_id', $project->id)
                                                ->where('list_type', config('constants.secondary'))
                                                ->where('bedrooms', $Bedrooms)
                                                ->where('area', $BuiltupArea)
                                                ->first();

                            }else{

                                $lastSubProject = Project::where('is_parent_project', 0)
                                                ->where('parent_project_id', $project->id)
                                                ->where('list_type', config('constants.secondary'))
                                                ->where('bedrooms', $Bedrooms)
                                                ->orderBy('created_at', 'desc') // Order by the 'created_at' column in descending order
                                                ->first(); // Use 'first()' to get the single record after ordering
                                
                                $titleParts = explode('-', $lastSubProject->title);

                                // Example usage
                                $part1 = $titleParts[0] ?? ''; // "1 BR"
                                $currentPart = $titleParts[1] ?? ''; // "A" (if it exists)

                                $nextPart = null;

                                if (ctype_alpha($currentPart) && strlen($currentPart) == 1) {
                                   
                                    
                                    // Convert the current character to its ASCII value
                                    $asciiValue = ord($currentPart);
                                
                                    // Increment the ASCII value
                                    $nextAsciiValue = $asciiValue + 1;
                                
                                    // Convert the new ASCII value back to a character
                                    $nextChar = chr($nextAsciiValue);
                                
                                    // Ensure the incremented character is valid (i.e., within A-Z range)
                                    if (ctype_alpha($nextChar) && strlen($nextChar) == 1) {
                                        $nextPart = $nextChar;
                                       
                                    } else {
                                        Log::info("Incremented character is not a valid single letter.");
                                    }
                                } else {
                                    Log::info("Current part is not a single alphabetic character.". $currentPart);
                                }
                                
                                

                                $subProject = new Project;
                                if($Bedrooms == "Studio"){
                                    $subProject->title = $Bedrooms." -".$nextPart;
                                }else{
                                    $subProject->title = $Bedrooms." BR-".$nextPart;
                                }
                                
                                $subProject->is_parent_project = 0;
                                $subProject->parent_project_id = $project->id;
                                $subProject->bedrooms = $Bedrooms;
                                $subProject->list_type = config('constants.secondary');
                                $subProject->area =  $BuiltupArea;
                                $subProject->builtup_area =  $BuiltupArea;
                                $subProject->starting_price = $Rent;
                                $subProject->user_id = $userID;
                                $subProject->accommodation_id = $propertyType->id;
                                $subProject->is_approved = config('constants.approved');
                                $subProject->status = config('constants.active');
                                $subProject->website_status = config('constants.available');
                                $subProject->save();

                            }
                        }else{

                            $subProject = new Project;
                            $subProject->title = $Bedrooms." BR-"."A";
                            $subProject->is_parent_project = 0;
                            $subProject->parent_project_id = $project->id;
                            $subProject->bedrooms = $Bedrooms;
                            $subProject->list_type = config('constants.secondary');
                            $subProject->area =  $BuiltupArea;
                            $subProject->builtup_area =  $BuiltupArea;
                            $subProject->starting_price = $Rent;
                            $subProject->user_id = $userID;
                            $subProject->accommodation_id = $propertyType->id;
                            $subProject->is_approved = config('constants.approved');
                            $subProject->status = config('constants.active');
                            $subProject->website_status = config('constants.available');
                            $subProject->save();
                        }                                
                    }else{
                        $subProject = new Project;
                        $subProject->title = $Bedrooms." BR-"."A";
                        $subProject->is_parent_project = 0;
                        $subProject->parent_project_id = $project->id;
                        $subProject->bedrooms = $Bedrooms;
                        $subProject->list_type = config('constants.secondary');
                        $subProject->area =  $BuiltupArea;
                        $subProject->builtup_area =  $BuiltupArea;
                        $subProject->starting_price = $Rent;
                        $subProject->user_id = $userID;
                        $subProject->accommodation_id = $propertyType->id;
                        $subProject->is_approved = config('constants.approved');
                        $subProject->status = config('constants.active');
                        $subProject->website_status = config('constants.available');
                        $subProject->save();
                    }

                    Log::info('$RefNo'.$RefNo);

                    if(Property::where('reference_number', $RefNo)->exists()){
                        $property = Property::where('reference_number', $RefNo)->first();
                    }else{
                        $property = new Property();
                    }

                    if($RefNo == 'AP7592'){
                        Log::info('Error Start-');
                        Log::info('communityName-'.$communityName);
                        Log::info('projectName-'.$projectName);
                        Log::info('MarketingTitle-'.$MarketingTitle);
                        Log::info('is_furniture-'.$is_furniture);
                        Log::info('PermitNumber-'.$PermitNumber);
                        Log::info('Remarks-'.$Remarks);
                        Log::info('NoOfBathrooms-'.$NoOfBathrooms);
                        Log::info('Bedrooms-'.$Bedrooms);
                        Log::info('BuiltupArea-'.$BuiltupArea);
                        Log::info('exclusive-'.$exclusive);
                        Log::info('Rent Price-'.$Rent);
                        Log::info($PrimaryUnitView);
                        Log::info('RefNo-'.$RefNo);
                        Log::info('User ID-'.$userID);
                        Log::info("agent id". $agentD->id);
                        Log::info(

                            "community id-".$community->id
                            

                        );
                        Log::info(

                            
                            "accommodation_id".$propertyType->id
                            
                            
                        );
                        Log::info(
                            "project_id".$project->id
                            
                            
                        );
                        Log::info(

                           "sub_project_id-".$subProject->id
                            
                        );

                        Log::info('Error End-');
                    }
                    $property->reference_number = $RefNo;
                    $property->name = $MarketingTitle;
                    $property->used_for = $propertyType->type;
                    $property->sub_title = $MarketingTitle;
                    $property->rental_period = 'Yearly';
                    $property->is_furniture = $is_furniture;
                    $property->emirate = $CityName;
                    $property->permit_number = $PermitNumber;
                    $property->short_description = $Remarks;
                    $property->description = $Remarks;
                    $property->bathrooms = $NoOfBathrooms;
                    $property->bedrooms = $Bedrooms;
                    $property->area = $BuiltupArea;
                    $property->builtup_area = $BuiltupArea;
                    $property->is_luxury_property = $exclusive;
                    $property->price = $Rent;
                    $property->is_feature = 0;
                    $property->exclusive = $exclusive;
                    $property->property_source = 'xml';
                    $property->primary_view = $PrimaryUnitView;
                    $property->is_display_home = 1;
                    if($ProGooglecoordinates){
                        $property->address_latitude = $latitude;
                        $property->address_longitude = $longitude;   
                    }
                    $property->new_reference_number = $RefNo;
                    $property->address = $community->name. " ". $CityName. " ".$CountryName;
                    $property->user_id = $userID;
                    $property->agent_id = $agentD->id;
                
                    $property->completion_status_id = 286;
                    $property->community_id = $community->id;
                    $property->accommodation_id = $propertyType->id;
                    $property->project_id = $project->id;
                    $property->sub_project_id = $subProject->id;
                    $property->status = config('constants.active');
                    $property->website_status = config('constants.available');
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
                                    $amenity->user_id   = $userID;
                                    
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
                        // Take the first image as the main image
                        $mainImage = isset($Images[0]) ? $Images[0] : '';
                        // Take the rest of the images as gallery images
                        $galleryImages = array_slice($Images, 1);
                        //Log::info('galleryImages');
                        //Log::info($galleryImages);
                    } else {
                       
                        $mainImage = '';
                        $galleryImages = [];
                    }
                    if ($mainImage) {
                        $property->addMediaFromUrl($mainImage['ImageURL'])->toMediaCollection('mainImages', 'propertyFiles');
                    }
                    
                    if ($galleryImages) {
                        $imageCount = 0; // Initialize the counter

                        foreach ($galleryImages as $key => $img) {

                            if ($imageCount >= 4) {
                                break; // Exit the loop if 4 images have been processed
                            }

                            $imageUrl = $img['ImageURL'];
                    

                            // Check if URL is reachable
                            $headers = @get_headers($imageUrl);
                            // Log::info('headers');
                            // Log::info($headers);
                    
                            // Follow redirect if necessary
                            $finalUrl = $imageUrl;
                            if (isset($headers[7])) {
                                $redirectUrl = $headers[7];
                                if (strpos($redirectUrl, 'Location:') === 0) {
                                    $finalUrl = trim(substr($redirectUrl, 10));
                                    Log::info('Redirected to: ' . $finalUrl);
                                }
                            }
                    
                            // Verify if the final URL is reachable
                             $finalHeaders = @get_headers($finalUrl);
                            // Log::info('Final headers');
                            // Log::info($finalHeaders);
                    
                            if ($finalHeaders && strpos($finalHeaders[0], '200') !== false) {
                                Log::info($finalUrl);
                                try {
                                    $property->addMediaFromUrl($finalUrl)
                                            ->withCustomProperties([
                                                'title' => $property->name,
                                                'order' => null
                                            ])
                                            ->toMediaCollection('subImages', 'propertyFiles');
                                            $imageCount++; // Increment the counter after successfully adding an image
                                } catch (\Exception $e) {
                                    Log::error("Error adding media from URL $finalUrl: " . $e->getMessage());
                                }
                            } else {
                                Log::warning("Final URL cannot be reached: $finalUrl");
                            }
                        }
                    }
                    
                    Log::info($Documents);
                    // Log the whole $Documents array for debugging
                    Log::info('Documents: ' . print_r($Documents, true));

                    if (isset($Documents) && is_array($Documents) && array_key_exists('Document', $Documents)) {
                        $document = $Documents['Document']; // This is a single associative array, not an array of arrays

                        // Log the type and content of $document
                        Log::info('Document type: ' . gettype($document));
                        Log::info('Document content: ' . print_r($document, true));

                        // Check if $document is an array and has the 'Title' key
                        if (is_array($document) && isset($document['Title']) && $document['Title'] === 'QR Code') {
                            $qrCodeURL = $document['URL'];
                            Log::info('QR Code URL: ' . $qrCodeURL);
                        } else {
                            Log::warning('QR Code not found or Title is incorrect.');
                        }
                    }

                    
                    Log::info("QR LINK".$qrCodeURL);
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
                        
                //     $originalAttributes = $property->getOriginal();

                //     $originalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
                //     $originalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));

                //     if ($property->amenities) {
                //             $newPropertyOriginalAttributes['amenityIds'] = $property->amenities->pluck('id')->toArray();
                //     } else {
                //             $newPropertyOriginalAttributes['amenityIds'] = [];
                //     }

                // // Log activity for developer creation
                // $properties = json_encode([
                //     'old' => [],
                //     'new' => $originalAttributes,
                //     'updateAttribute' => [],
                //     'attribute' => []
                // ]);
                // logActivity('New Property has been created', $property->id, Property::class, $properties);
                    Log::info('$property->website_status');
                    Log::info($property->website_status);

                if ($property->website_status == config('constants.available') ) {

                    $project = $property->project; // Assuming 'project' is the relationship name
                    Log::info('$project->website_status'.$project->website_status. "is_VAlid". $property->is_valid);
                    Log::info($project->website_status == config('constants.available') && $property->is_valid == 1);

                    //$notValidProject = $property->where('is_valid', '!=', 1)->exists();

                    if($project->website_status != config('constants.available')){

                        $property->status = config('constants.active');
                        $property->website_status = config('constants.requested');
                        $property->save();

                    }elseif ($property->is_valid == 0) {
                        
                        $property->status = config('constants.inactive');
                        $property->website_status = config('constants.NA');
                        $property->save();


                        // $newPropertyOriginalAttributes = $property->getOriginal();

                        // if ($property->amenities) {
                        //     $newPropertyOriginalAttributes['amenityIds'] = $property->amenities->pluck('id')->toArray();
                        // } else {
                        //     $newPropertyOriginalAttributes['amenityIds'] = [];
                        // }


                        // if (isset($property->description)) {
                        //     $newPropertyOriginalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));
                        // }
                        // if (isset($property->short_description)) {
                        //     $newPropertyOriginalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
                        // }

                        // $properties = getUpdatedPropertiesForProperty($newPropertyOriginalAttributes, $originalAttributes);

                        // logActivity('Property marked as NA due to missing to Permit Number and QR ', $property->id, Property::class, $properties);
                    }elseif($project->website_status == config('constants.available') && $property->is_valid == 1){
                        Log::info('invalid');
                        $property->status = config('constants.active');
                        $property->website_status = config('constants.available');
                        $property->save();
                    }
                }

                    Log::info("communityId=".$community->id);
                    Log::info("projectName=".$project->title);
                    Log::info("projectId=".$project->id);
                    Log::info("propertyId=".$property->id);
                //}
                $counter++;
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
            Log::info($response);
            //return response()->json($response, 500);
        }
    }
}
