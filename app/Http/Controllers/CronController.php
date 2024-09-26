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
    Project,
    User,
    WebsiteSetting

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
use App\Jobs\{
    GoyzerSaleProperties,
    GoyzerRentalProperties
};
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CronController extends Controller
{

    public function makePropertiesUpdated()
    {
        
        $projectIds = [ 496, 499, 613, 642, 787, 791, 857, ];
        try {
            $properties = Property::whereIn('project_id', $projectIds)
            ->where('out_of_inventory', 0)
            ->where('property_source', 'crm')
            ->latest()->get();
          
            foreach ($properties as $property) {

                $project = Project::find($property->project_id);


                $originalAttributes = $property->getOriginal();
                $originalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
                $originalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));

                if ($property->amenities) {
                    $originalAttributes['amenityIds'] = $property->amenities->pluck('id')->toArray();
                } else {
                    $originalAttributes['amenityIds'] = [];
                }

                $property->permit_number = $project->permit_number;

                if(!empty($project->qr_link)){
                    $property->clearMediaCollection('qrs');
                    $property->addMediaFromUrl($project->qr_link)->toMediaCollection('qrs', 'propertyFiles' );
                }

                $property->save();
                if($property->qr){
                    $property->qr_link = $property->qr;
                }
                $property->save();

               
                if (!empty($property->permit_number) && !empty($property->qr_link)) {
                    if($property->qr_link){
                        $property->is_valid = 1;
                    }else{
                        $property->is_valid = 0; // Optionally set to false if not valid
                    }
                   
                } else {
                    $property->is_valid = 0; // Optionally set to false if not valid
                }
                $property->save();
                if($property->is_valid == 1 && $property->website_status == config('constants.NA')){
                    $property->status = config('constants.active');
                    $property->website_status = config('constants.available');
                }elseif($property->is_valid == 0 && $property->website_status == config('constants.available')){
                    $property->status = config('constants.Inactive');
                    $property->website_status = config('constants.NA');
                }
                
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

                $properties = $this->getUpdatedProperties($newPropertyOriginalAttributes, $originalAttributes);

                // if($property->website_status == config('constants.available')){

                //     logActivity('Property marked as Available as Permit Number and QR Exist', $property->id, Property::class, $properties);
                // }else{
                //     logActivity('Property Update as project updated', $property->id, Property::class, $properties);
                // }
                
            }
        } catch (\Exception $error) {
            // Return error response
            return [
                'success' => false,
                'message' => $error->getMessage(),
            ];
        }
    }
    public function getUpdatedProperties($newProjectOriginalAttributes, $originalAttributes)
    {
        Log::info('newProjectOriginalAttributes', $newProjectOriginalAttributes);
        Log::info('originalAttributes', $originalAttributes);

        // Convert specific attributes to integer arrays if they exist
        $keysToConvert = ['developerIds', 'amenityIds', 'highlightIds'];

        foreach ($keysToConvert as $key) {
            if (isset($newProjectOriginalAttributes[$key]) && is_array($newProjectOriginalAttributes[$key])) {
                $newProjectOriginalAttributes[$key] = array_map('intval', $newProjectOriginalAttributes[$key]);
            }
            if (isset($originalAttributes[$key]) && is_array($originalAttributes[$key])) {
                $originalAttributes[$key] = array_map('intval', $originalAttributes[$key]);
            }
        }

        // Determine the updated attributes
        $updatedAttributes = [];

        foreach ($newProjectOriginalAttributes as $key => $value) {
            if (!in_array($key, ['created_at', 'updated_at'])) {
                // Ensure the original attribute exists and compare based on type
                if (array_key_exists($key, $originalAttributes)) {
                    if (is_string($value) && $originalAttributes[$key] != $value) {
                        $updatedAttributes[$key] = $value;
                    } elseif (is_array($value) && serialize($originalAttributes[$key]) !== serialize($value)) {
                        $updatedAttributes[$key] = $value;
                    }
                } else {
                    // Handle case where $originalAttributes[$key] does not exist
                    $updatedAttributes[$key] = $value;
                }
            }
        }

        // Construct the updated attributes strings
        $updatedAttributesString = implode(', ', array_map(
            fn ($value, $key) => "$key: " . (is_array($value) ? json_encode($value) : $value),
            $updatedAttributes,
            array_keys($updatedAttributes)
        ));

        $updatedCoumnAttributesString = implode(', ', array_keys($updatedAttributes));

        // Encode the properties to JSON
        $properties = json_encode([
            'old' => $originalAttributes,
            'new' => $newProjectOriginalAttributes,
            'updateAttribute' => $updatedCoumnAttributesString,
            'attribute' => $updatedAttributesString
        ]);

        return $properties;
    }

    public function webQRCode()
    {
        try{
            $key = 'WEB_QR';
            $setting = WebsiteSetting::where('key', $key)->first();
            $url = config('app.frontend_url');
            
            $qrCode = QrCode::format('png')->size(200)->generate($url);

            $imageName = 'website.png';
            Storage::disk('websiteQRFiles')->put($imageName, $qrCode);
            $qrCodeUrl = Storage::disk('websiteQRFiles')->url($imageName);

            $setting->clearMediaCollection('QRs');
            $setting->addMediaFromUrl($qrCodeUrl)->usingFileName($imageName)->toMediaCollection('QRs', 'generalFiles');
            $setting->save();
            dd($setting->getFirstMediaUrl('generalFiles'));


        }catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()]);
        }
        
    }

    public function contactInsert()
    {

// Example dynamic values
$name = "fsf";
$email = "aqsa@xpertise.ae";
$Remarks = 'Hi, I am interested in your property on website: https://www.range.ae/properties/spacious-1-br-apt-study-prime-location';
$countryCode = '971';
$areaCode = '58';
$phoneNumber = '6238697';
$property = (object) [
    'CountryID' => '65946',
    'StateID' => '55367',
    'CityID' => '54788',
    'DistrictID' => '80120',
    'CommunityID' => '199636',
    'SubCommunityID' => '201991',
    'PropertyID' => '2264',
    'UnitID' => '7404',
    'UnitType' => '19',
];


// Manually encode the special characters in the access code
$accessCode = urlencode('$R@nGe!NteRn@t!on@l');  // Encodes special characters
$name = urlencode($name);
$countryCode = urlencode($countryCode);
$areaCode = urlencode($areaCode);
$phoneNumber = urlencode($phoneNumber);
$email = urlencode($email);
$Remarks = urlencode($Remarks);


$accessCode = '$R@nGe!NteRn@t!on@l';

// Define parameters as key-value pairs
$params = [
    'GroupCode' => '5084',
    'TitleID' => '79743',
    'FirstName' => $name,
    'FamilyName' => $name,
    'MobileCountryCode' => $countryCode,
    'MobileAreaCode' => $areaCode,
    'MobilePhone' => $phoneNumber,
    'TelephoneCountryCode' => '',
    'TelephoneAreaCode' => '',
    'Telephone' => '',
    'Email' => $email,
    'NationalityID' => '',
    'CompanyID' => '',
    'Remarks' => '',
    'RequirementType' => '91212',
    'ContactType' => '1',
    'CountryID' => $property->CountryID,
    'StateID' => $property->StateID,
    'CityID' => $property->CityID,
    'DistrictID' => $property->DistrictID,
    'CommunityID' => $property->CommunityID,
    'SubCommunityID' => $property->SubCommunityID,
    'PropertyID' => $property->PropertyID,
    'UnitID' => $property->UnitID,
    'UnitType' => $property->UnitType,
    'MethodOfContact' => '196061',
    'MediaType' => '79266',
    'MediaName' => '78340',
    'ReferredByID' => '1000',
    'ReferredToID' => '1219',
    'DeactivateNotification' => '0.0.0.0',
    'Bedroom' => '2',
    'Budget' => '',
    'Budget2' => '',
    'RequirementCountryID' => '',
    'ExistingClient' => '',
    'CompaignSource' => '',
    'CompaignMedium' => '',
    'Company' => '',
    'NumberOfEmployee' => '',
    'LeadStageId' => '2',
    'ActivityDate' => '',
    'ActivityTime' => '',
    'ActivityTypeId' => '',
    'ActivitySubject' => '',
    'ActivityRemarks' => ''
];

// Manually build the query string
$queryString = '';
foreach ($params as $key => $value) {
    
        $queryString .= urlencode($key) . '=' . urlencode($value) . '&';
   
}
$queryString = rtrim($queryString, '&');

// Construct the final URL
$finalUrl = 'https://webapi.goyzer.com/Company.asmx/ContactInsert2?AccessCode='.$accessCode.'&'.$queryString;

Log::info('Final URL');
Log::info($finalUrl);

// Send the request
$response = Http::get($finalUrl);

if ($response->successful()) {
    Log::info("success");
    print_r($response->body());
} else {
    print_r($response->status());
}

  
    }
    public function testEmail()
    {
        Log::info('MonthlyWebsiteStateReportJob Start');
        try {
            if (Carbon::now()->isMonday()) {
                Log::info('MonthlyWebsiteStateReportJob Start- on monday');
                $recipients = [
                    ['name' => 'Aqsa', 'email' => 'aqsa@xpertise.ae'],
                    // ['name' => 'Nitin Chopra', 'email' => 'nitin@range.ae'],
                    // ['name' => 'Lester Verma', 'email' => 'lester@range.ae'],
                ];
            } else {
                Log::info('MonthlyWebsiteStateReportJob Start- on otherDay');
                $recipients = [
                    ['name' => 'Aqsa', 'email' => 'aqsa@xpertise.ae'],
                    // ['name' => 'Nitin Chopra', 'email' => 'nitin@range.ae'],
                    // ['name' => 'Lester Verma', 'email' => 'lester@range.ae'],
                    // ['name' => 'Romit Kumar', 'email' => 'romit@range.ae'],
                    // ['name' => 'Safeena Ahmad', 'email' => 'safeeena@xpertise.ae'],
                ];
            }



            sendWebsiteStatReport($recipients);
        } catch (\Exception $error) {
            Log::info("MonthlyWebsiteStateReportJob-error" . $error->getMessage());
        }
    

    }
    public function propertiesPermitNumber()
    {

        // DB::beginTransaction();
        // try {
           
        //         $properties = Property::latest()->get();
                
        //         foreach($properties as $property){
        //             Log::info("propertyID-".$property->id);
        //             echo "propertyID-".$property->id;
        //             Property::getModel()->timestamps = false;
        //             $property->qr_link = $property->qr;
        //             $property->save();

        //             if (!empty($property->permit_number) && !empty($property->qr_link)) {
        //                 $property->is_valid = 1;
        //             } else {
        //                 $property->is_valid = 0; // Optionally set to false if not valid
        //             }
        //             $property->save();

        //             Property::getModel()->timestamps = true;
        //         }
           
        //     DB::commit();
        // } catch (\Exception $error) {
        //     return response()->json(['error' => $error->getMessage()]);
        // }


        DB::beginTransaction();
        try {
            $projects = Project::where('is_parent_project', 1)->whereIn('id', [790, 843])->orderBy('id', 'asc')->get();
           
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

    public function getSaleListings()
    {

        Log::info('getSaleListings');
        // DB::beginTransaction();
        // try{
        //     $today = Carbon::now();
        //     $user = User::where('email', 'goyzer@gmail.com')->first();
        //     $userID = $user->id;

        //     //Log::info($userID);

        //     $feed = 'https://webapi.goyzer.com/Company.asmx/SalesListings?AccessCode=$R@nGe!NteRn@t!on@l&GroupCode=5084&Bedrooms=&StartPriceRange=&EndPriceRange=&categoryID=&SpecialProjects=&CountryID=&StateID=&CommunityID=&DistrictID=&FloorAreaMin=&FloorAreaMax=&UnitCategory=&UnitID=&BedroomsMax=&PropertyID=&ReadyNow=&PageIndex=';

        //    // $feed = 'https://webapi.goyzer.com/Company.asmx/SalesListings?AccessCode='.env('API_ACCESS_CODE').'&GroupCode='.env('API_GROUP_CODE').'&PropertyType=&Bedrooms=&StartPriceRange=&EndPriceRange=&categoryID=&CountryID=&StateID=&CommunityID=&FloorAreaMin=&FloorAreaMax=&UnitCategory=&UnitID=&BedroomsMax=&PropertyID=&ReadyNow=&PageIndex=';
        //     $xml_arr  = simplexml_load_file($feed,'SimpleXMLElement',LIBXML_NOCDATA);        
        //     $xml_arr  = json_decode(json_encode($xml_arr,true),true);
    
            
        //     if (isset($xml_arr['UnitDTO']) && !empty($xml_arr['UnitDTO'])) {
        //         $properties = $xml_arr['UnitDTO'];


        //         $CRMProperties = Property::where('property_source', 'xml')->where('category_id', 8)->get();

        //         foreach ($CRMProperties as $prop) {
                    
        //             $flag = 0;
        //             foreach ($properties as $key => $value) {
        //                 if ($prop['reference_number'] == $value['RefNo']) {
        //                     $flag = 1;
        //                     break;
        //                 } else {
        //                     $flag = 0;
        //                 }
        //             }
        //             if ($flag == 0) {
        //                 $propDel = Property::where('id', '=', $prop['id'])->first();
        //                 $propDel->delete();
        //             }
        //         }
        //         $counter = 0;
        //         $limitedProperties = array_slice($properties, 0, 50);

        //         foreach($properties as $index=>$rental){
        //             if ($counter >= 50) {
        //                 break;  // Exit the loop after processing 26 elements
        //             }
                    
        //             //if($rental['RefNo'] == 'AP7466'){

        //             Log::info($index);
        //            $qrCodeURL = NULL;

        //             $RefNo = isset($rental['RefNo']) ? $rental['RefNo'] : '';
                   
        //             $communityName = isset($rental['Community']) ? $rental['Community'] : '';
                   
        //             $accommodationName = isset($rental['Category']) ? $rental['Category'] : ''; 
        //             $projectName = isset($rental['PropertyName']) ? $rental['PropertyName'] : ''; 
        //             $BuiltupArea = isset($rental['BuiltupArea']) ? $rental['BuiltupArea'] : '';
        //             $PrimaryUnitView = isset($rental['PrimaryUnitView']) 
        //             ? (is_array($rental['PrimaryUnitView']) && empty($rental['PrimaryUnitView']) ? null : $rental['PrimaryUnitView'])
        //             : '';

        //             $SecondaryUnitView = isset($rental['SecondaryUnitView']) 
        //             ? (is_array($rental['SecondaryUnitView']) && empty($rental['SecondaryUnitView']) ? null : $rental['SecondaryUnitView'])
        //             : '';


        //             $SecondaryUnitView = isset($rental['SecondaryUnitView']) ? $rental['SecondaryUnitView'] : '';
        //             $HandoverDate = isset($rental['HandoverDate']) ? $rental['HandoverDate'] : '';
        //             if($HandoverDate){
        //                 $HandoverDate = date('Y-m-d', strtotime($HandoverDate));
        //             }
        //             $Agent = isset($rental['Agent']) ? $rental['Agent'] : '';
        //             $ContactNumber = isset($rental['ContactNumber']) ? $rental['ContactNumber'] : '';
        //             $StateName = isset($rental['StateName']) ? $rental['StateName'] : '';
        //             $Remarks = isset($rental['Remarks']) ? $rental['Remarks'] : '';
        //             $Remarks = str_replace(['<![CDATA[', ']]>'], '', $Remarks);

        //             $CountryName = isset($rental['CountryName']) ? $rental['CountryName'] : '';
        //             $CityName = isset($rental['CityName']) ? $rental['CityName'] : '';
        //             $DistrictName = isset($rental['DistrictName']) ? $rental['DistrictName'] : '';
        //             $Rent = isset($rental['SellPrice']) ? $rental['SellPrice'] : '';
        //             $ProGooglecoordinates = isset($rental['ProGooglecoordinates']) ? $rental['ProGooglecoordinates'] : '';
        //             $SalesmanEmail = isset($rental['SalesmanEmail']) ? $rental['SalesmanEmail'] : '';
        //             $MarketingTitle = isset($rental['MarketingTitle']) ? $rental['MarketingTitle'] : '';
        //             $MarketingOptions = isset($rental['MarketingOptions']) ? $rental['MarketingOptions'] : '';
        //             $Mandate = isset($rental['Mandate']) ? $rental['Mandate'] : '';
        //             $RentPerMonth = isset($rental['RentPerMonth']) ? $rental['RentPerMonth'] : '';
        //             $Rent = isset($rental['SellPrice']) ? $rental['SellPrice'] : '';
        //             $ReraStrNo = isset($rental['ReraStrNo']) ? $rental['ReraStrNo'] : '';
        //             //$PermitNumber = isset($rental['PermitNumber']) ? $rental['PermitNumber'] : '';

        //             $PermitNumber = isset($rental['PermitNumber']) 
        //             ? (is_array($rental['PermitNumber']) && empty($rental['PermitNumber']) ? null : $rental['PermitNumber'])
        //             : '';


        //             $Images = isset($rental['Images']) ? $rental['Images'] : '';
        //             $FittingFixtures = isset($rental['FittingFixtures']) ? $rental['FittingFixtures'] : '';
        //             $Furnish_status = isset($rental['Furnish_status']) ? $rental['Furnish_status'] : '';
        //             $Mandate = isset($rental['Mandate']) ? $rental['Mandate'] : '';
        //             $Documents = isset($rental['Documents']) ? $rental['Documents'] : '';

                    
        //             if($ProGooglecoordinates){
        //                 list($longitude, $latitude) = explode(',', $ProGooglecoordinates);
        //                 $latitude = trim($latitude);
        //                 $longitude = trim($longitude);
        //             }else{
        //                 $latitude = null;
        //                 $longitude = null;
        //             }
                   

        //             if($Mandate == 'Exclusive'){
        //                 $exclusive = 1;
        //             }else{
        //                 $exclusive = 0;
        //             }
        //             if($Furnish_status == 'Furnished'){
        //                 $is_furniture = 0;
        //             }elseif($Furnish_status == "Unfurnished"){
        //                 $is_furniture = 1;
        //             }else{
        //                 $is_furniture = 'partly';
        //             }
                   
        //             $NoOfBathrooms = isset($rental['NoOfBathrooms']) ? $rental['NoOfBathrooms'] : '';
        //             //$NoOfBathrooms = 2;
        //             if (is_array($NoOfBathrooms)) {
        //                 // If $Bedrooms is an array, take the first element
        //                 $NoOfBathrooms = !empty($NoOfBathrooms) ? $NoOfBathrooms[0] : '';
        //             } elseif (is_string($NoOfBathrooms)) {
        //                 // If $Bedrooms is already a string, use it directly
        //                 $NoOfBathrooms = $NoOfBathrooms;
        //             } else {
        //                 // Handle cases where $Bedrooms is neither an array nor a string
        //                 $NoOfBathrooms = '';
        //             }

                    

        //             $Bedrooms = isset($rental['Bedrooms']) ? $rental['Bedrooms'] : '';
        //             //$Bedrooms =2;
        //             if (is_array($Bedrooms)) {
        //                 // If $Bedrooms is an array, take the first element
        //                 $Bedrooms = !empty($Bedrooms) ? $Bedrooms[0] : '';
        //             } elseif (is_string($Bedrooms)) {
        //                 // If $Bedrooms is already a string, use it directly
        //                 $Bedrooms = $Bedrooms;
        //             } else {
        //                 // Handle cases where $Bedrooms is neither an array nor a string
        //                 $Bedrooms = '';
        //             }
        //             if($Bedrooms == 0 ){
        //                 $Bedrooms = 'Studio';
        //             }
                    
        //             if (Agent::where('email', $SalesmanEmail)->orWhere('secondary_email', $SalesmanEmail)->exists()) {
        //                 // Fetch existing agent
        //                 $agentD = Agent::where('email', $SalesmanEmail)
        //                                ->orWhere('secondary_email', $SalesmanEmail)
        //                                ->first();
        //             } else {
        //                 // Create a new agent
        //                 $agentD = new Agent;
        //                 $agentD->name = $Agent;
        //                 $agentD->email = $SalesmanEmail;
        //                 $agentD->status = 'Inactive';  // Correct variable name
        //                 $agentD->user_id = $userID;
        //                 $agentD->save();
        //             }
                   
                    
        //             if(Accommodation::where('name', 'like', "%$accommodationName%")->exists()){
        //                 $propertyType = Accommodation::where('name', 'like', "%$accommodationName%")->first();
        //             }else{
        //                 $propertyType = new Accommodation;
        //                 $propertyType->name = $accommodationName;
        //                 $propertyType->user_id = $userID;
        //                 $propertyType->save();
        //             }
        //             if(Community::where('name', 'like', "%$communityName%")->orWhere('name_1', 'like', "%$communityName%")->exists()){

        //                 $community = Community::where('name', 'like', "%$communityName%")->orWhere('name_1', 'like', "%$communityName%")->first();

        //             }else{
        //                 $community = new Community;
        //                 $community->name = $communityName;
        //                 $community->is_approved = config('constants.requested');
        //                 $community->status = config('constants.active');
        //                 $community->website_status = config('constants.requested');
        //                 $community->community_source = 'xml';

        //                 $community->address = $community->name. " ". $CityName. " ".$CountryName;

        //                 if($ProGooglecoordinates){
                           
        //                     $community->address_latitude = $latitude;
        //                     $community->address_longitude = $longitude;
                            
        //                 }

        //                 $community->user_id = $userID;
        //                 $community->save();
                        
        //             }

                    
                   
        //             if(Project::where('title', 'like', "%$projectName%")->orWhere('title_1', 'like', "%$projectName%")->where('is_parent_project', 1)->exists()){
        //                 $project = Project::where('title', 'like', "%$projectName%")->orWhere('title_1', 'like', "%$projectName%")->where('is_parent_project', 1)->first();
        //             }else{
        //                 $project = new Project;
                      
        //                 $titleArray = explode(' ', $projectName);
        //                 $sub_title = $titleArray[0];

        //                 $subTitle1 = array_shift($titleArray);
        //                 $sub_title_1 = implode(" ",  $titleArray);
        //                 $project->title = $projectName;
        //                 $project->sub_title = $sub_title;
        //                 $project->sub_title_1 = $sub_title_1;
        //                 $project->is_approved = config('constants.requested');
        //                 $project->status = config('constants.active');
        //                 $project->website_status = config('constants.requested');
        //                 $project->project_source = 'xml';
        //                 $project->community_id = $community->id;
        //                 $project->accommodation_id = $propertyType->id;
        //                 $project->bathrooms = $Bedrooms;
        //                 $project->address = $community->name. " ". $CityName. " ".$CountryName;
        //                 //$project->completion_date = $HandoverDate;

        //                 if ($HandoverDate > $today) {
        //                     $project->completion_status_id = config('constants.underConstruction');
        //                 } else {
        //                     $project->completion_status_id = config('constants.completed');
        //                 }

        //                 if($ProGooglecoordinates){
        //                     $project->address_latitude = $latitude;
        //                     $project->address_longitude = $longitude;
        //                 }
                        
        //                 $project->user_id = $userID;
        //                 $project->is_parent_project = 1; 
        //                 $project->save();
        //             }
        //             $subProjectTypeExist =  Project::where('is_parent_project', 0)->where('parent_project_id', $project->id)->where('list_type', config('constants.secondary'))->exists(); 
        //             if($subProjectTypeExist){
        //                 $subProjectTypeBedroomExist =  Project::where('is_parent_project', 0)
        //                                                 ->where('parent_project_id', $project->id)
        //                                                 ->where('list_type', config('constants.secondary'))
        //                                                 ->where('bedrooms', $Bedrooms)
        //                                                 ->exists();
                        
        //                 if($subProjectTypeBedroomExist){
        //                     $subProjectTypeBedroomAreaExist =  Project::where('is_parent_project', 0)
        //                                                 ->where('parent_project_id', $project->id)
        //                                                 ->where('list_type', config('constants.secondary'))
        //                                                 ->where('bedrooms', $Bedrooms)
        //                                                 ->where('area', $BuiltupArea)
        //                                                 ->exists();
        //                     if($subProjectTypeBedroomAreaExist){
                                
        //                         $subProject =  Project::where('is_parent_project', 0)
        //                                         ->where('parent_project_id', $project->id)
        //                                         ->where('list_type', config('constants.secondary'))
        //                                         ->where('bedrooms', $Bedrooms)
        //                                         ->where('area', $BuiltupArea)
        //                                         ->first();

        //                     }else{

        //                         $lastSubProject = Project::where('is_parent_project', 0)
        //                                         ->where('parent_project_id', $project->id)
        //                                         ->where('list_type', config('constants.secondary'))
        //                                         ->where('bedrooms', $Bedrooms)
        //                                         ->orderBy('created_at', 'desc') // Order by the 'created_at' column in descending order
        //                                         ->first(); // Use 'first()' to get the single record after ordering
                                
        //                         $titleParts = explode('-', $lastSubProject->title);

        //                         // Example usage
        //                         $part1 = $titleParts[0] ?? ''; // "1 BR"
        //                         $currentPart = $titleParts[1] ?? ''; // "A" (if it exists)

        //                         $nextPart = null;

        //                         if (ctype_alpha($currentPart) && strlen($currentPart) == 1) {
                                   
                                    
        //                             // Convert the current character to its ASCII value
        //                             $asciiValue = ord($currentPart);
                                
        //                             // Increment the ASCII value
        //                             $nextAsciiValue = $asciiValue + 1;
                                
        //                             // Convert the new ASCII value back to a character
        //                             $nextChar = chr($nextAsciiValue);
                                
        //                             // Ensure the incremented character is valid (i.e., within A-Z range)
        //                             if (ctype_alpha($nextChar) && strlen($nextChar) == 1) {
        //                                 $nextPart = $nextChar;
                                       
        //                             } else {
        //                                 Log::info("Incremented character is not a valid single letter.");
        //                             }
        //                         } else {
        //                             Log::info("Current part is not a single alphabetic character.". $currentPart);
        //                         }
                                
                                

        //                         $subProject = new Project;
        //                         if($Bedrooms == "Studio"){
        //                             $subProject->title = $Bedrooms." -".$nextPart;
        //                         }else{
        //                             $subProject->title = $Bedrooms." BR-".$nextPart;
        //                         }
                                
        //                         $subProject->is_parent_project = 0;
        //                         $subProject->parent_project_id = $project->id;
        //                         $subProject->bedrooms = $Bedrooms;
        //                         $subProject->list_type = config('constants.secondary');
        //                         $subProject->area =  $BuiltupArea;
        //                         $subProject->builtup_area =  $BuiltupArea;
        //                         $subProject->starting_price = $Rent;
        //                         $subProject->user_id = $userID;
        //                         $subProject->accommodation_id = $propertyType->id;
        //                         $subProject->is_approved = config('constants.approved');
        //                         $subProject->status = config('constants.active');
        //                         $subProject->website_status = config('constants.available');
        //                         $subProject->save();

        //                     }
        //                 }else{

        //                     $subProject = new Project;
        //                     $subProject->title = $Bedrooms." BR-"."A";
        //                     $subProject->is_parent_project = 0;
        //                     $subProject->parent_project_id = $project->id;
        //                     $subProject->bedrooms = $Bedrooms;
        //                     $subProject->list_type = config('constants.secondary');
        //                     $subProject->area =  $BuiltupArea;
        //                     $subProject->builtup_area =  $BuiltupArea;
        //                     $subProject->starting_price = $Rent;
        //                     $subProject->user_id = $userID;
        //                     $subProject->accommodation_id = $propertyType->id;
        //                     $subProject->is_approved = config('constants.approved');
        //                     $subProject->status = config('constants.active');
        //                     $subProject->website_status = config('constants.available');
        //                     $subProject->save();
        //                 }                                
        //             }else{
        //                 $subProject = new Project;
        //                 $subProject->title = $Bedrooms." BR-"."A";
        //                 $subProject->is_parent_project = 0;
        //                 $subProject->parent_project_id = $project->id;
        //                 $subProject->bedrooms = $Bedrooms;
        //                 $subProject->list_type = config('constants.secondary');
        //                 $subProject->area =  $BuiltupArea;
        //                 $subProject->builtup_area =  $BuiltupArea;
        //                 $subProject->starting_price = $Rent;
        //                 $subProject->user_id = $userID;
        //                 $subProject->accommodation_id = $propertyType->id;
        //                 $subProject->is_approved = config('constants.approved');
        //                 $subProject->status = config('constants.active');
        //                 $subProject->website_status = config('constants.available');
        //                 $subProject->save();
        //             }

        //             Log::info('$RefNo'.$RefNo);

        //             if(Property::where('reference_number', $RefNo)->exists()){
        //                 $property = Property::where('reference_number', $RefNo)->first();
        //             }else{
        //                 $property = new Property();
        //             }

        //             if($RefNo == 'AP7592'){
        //                 Log::info('Error Start-');
        //                 Log::info('communityName-'.$communityName);
        //                 Log::info('projectName-'.$projectName);
        //                 Log::info('MarketingTitle-'.$MarketingTitle);
        //                 Log::info('is_furniture-'.$is_furniture);
        //                 Log::info('PermitNumber-'.$PermitNumber);
        //                 Log::info('Remarks-'.$Remarks);
        //                 Log::info('NoOfBathrooms-'.$NoOfBathrooms);
        //                 Log::info('Bedrooms-'.$Bedrooms);
        //                 Log::info('BuiltupArea-'.$BuiltupArea);
        //                 Log::info('exclusive-'.$exclusive);
        //                 Log::info('Rent Price-'.$Rent);
        //                 Log::info($PrimaryUnitView);
        //                 Log::info('RefNo-'.$RefNo);
        //                 Log::info('User ID-'.$userID);
        //                 Log::info("agent id". $agentD->id);
        //                 Log::info(

        //                     "community id-".$community->id
                            

        //                 );
        //                 Log::info(

                            
        //                     "accommodation_id".$propertyType->id
                            
                            
        //                 );
        //                 Log::info(
        //                     "project_id".$project->id
                            
                            
        //                 );
        //                 Log::info(

        //                    "sub_project_id-".$subProject->id
                            
        //                 );

        //                 Log::info('Error End-');
        //             }
        //             $property->reference_number = $RefNo;
        //             $property->name = $MarketingTitle;
        //             $property->used_for = $propertyType->type;
        //             $property->sub_title = $MarketingTitle;
        //             $property->rental_period = 'Yearly';
        //             $property->is_furniture = $is_furniture;
        //             $property->emirate = $CityName;
        //             $property->permit_number = $PermitNumber;
        //             $property->short_description = $Remarks;
        //             $property->description = $Remarks;
        //             $property->bathrooms = $NoOfBathrooms;
        //             $property->bedrooms = $Bedrooms;
        //             $property->area = $BuiltupArea;
        //             $property->builtup_area = $BuiltupArea;
        //             $property->is_luxury_property = $exclusive;
        //             $property->price = $Rent;
        //             $property->is_feature = 0;
        //             $property->exclusive = $exclusive;
        //             $property->property_source = 'xml';
        //             $property->primary_view = $PrimaryUnitView;
        //             $property->is_display_home = 1;
        //             if($ProGooglecoordinates){
        //                 $property->address_latitude = $latitude;
        //                 $property->address_longitude = $longitude;   
        //             }
        //             $property->new_reference_number = $RefNo;
        //             $property->address = $community->name. " ". $CityName. " ".$CountryName;
        //             $property->user_id = $userID;
        //             $property->agent_id = $agentD->id;
                
        //             $property->completion_status_id = 286;
        //             $property->community_id = $community->id;
        //             $property->accommodation_id = $propertyType->id;
        //             $property->project_id = $project->id;
        //             $property->sub_project_id = $subProject->id;
        //             $property->status = config('constants.active');
        //             $property->website_status = config('constants.available');
        //             $property->category_id = 8;
        //             $property->save();
        //             if ($property->category_id = 8) {
        //                 $prefix = 'S';
        //             } else {
        //                 $prefix = 'R';
        //             }
                
        
        //             if (!empty($property->permit_number) && !empty($property->qr_link)) {
        //                 $property->is_valid = 1;
        //             } else {
        //                 $property->is_valid = 0; // Optionally set to false if not valid
        //             }

        //             $property->save();
                    


        //             // amenities code start
                    
        //             if ($FittingFixtures && (count($FittingFixtures) > 0)) {
                      
                        
        //                 foreach ($FittingFixtures as $keys => $facilityArray) {

        //                     foreach($facilityArray as $faci){
        //                         $faci = $faci['Name'];
        //                         $checkFC = Amenity::where('name', $faci)->exists();
        //                         if($checkFC){
        //                             $checkFC = Amenity::where('name', $faci)->first();
        //                             $project->amenities()->attach($checkFC->id);
        //                             $property->amenities()->attach($checkFC->id);
        //                         }else{

        //                             $amenity = new Amenity;
        //                             $amenity->name = $faci;
        //                             $amenity->status = 'Inactive';
        //                             $amenity->is_approved = config('constants.requested');
        //                             $amenity->status = config('constants.Inactive');
        //                             $amenity->user_id   = $userID;
                                    
        //                             $amenity->save();
        //                             $project->amenities()->attach($amenity->id);
        //                             $property->amenities()->attach($amenity->id);
        //                         }
        //                     }
                            
                            
                            
        //                 }
        //             }
                    
        //             // amenities code end
                   
        //             if (isset($Images) && is_array($Images) && array_key_exists('Image', $Images)) {
        //                 $Images = $Images['Image'];
        //                 // Take the first image as the main image
        //                 $mainImage = isset($Images[0]) ? $Images[0] : '';
        //                 // Take the rest of the images as gallery images
        //                 $galleryImages = array_slice($Images, 1);
        //                 //Log::info('galleryImages');
        //                 //Log::info($galleryImages);
        //             } else {
                       
        //                 $mainImage = '';
        //                 $galleryImages = [];
        //             }
        //             if ($mainImage) {
        //                 $property->addMediaFromUrl($mainImage['ImageURL'])->toMediaCollection('mainImages', 'propertyFiles');
        //             }
                    
        //             if ($galleryImages) {
        //                 $imageCount = 0; // Initialize the counter

        //                 foreach ($galleryImages as $key => $img) {

        //                     if ($imageCount >= 4) {
        //                         break; // Exit the loop if 4 images have been processed
        //                     }

        //                     $imageUrl = $img['ImageURL'];
                    

        //                     // Check if URL is reachable
        //                     $headers = @get_headers($imageUrl);
        //                     // Log::info('headers');
        //                     // Log::info($headers);
                    
        //                     // Follow redirect if necessary
        //                     $finalUrl = $imageUrl;
        //                     if (isset($headers[7])) {
        //                         $redirectUrl = $headers[7];
        //                         if (strpos($redirectUrl, 'Location:') === 0) {
        //                             $finalUrl = trim(substr($redirectUrl, 10));
        //                             Log::info('Redirected to: ' . $finalUrl);
        //                         }
        //                     }
                    
        //                     // Verify if the final URL is reachable
        //                      $finalHeaders = @get_headers($finalUrl);
        //                     // Log::info('Final headers');
        //                     // Log::info($finalHeaders);
                    
        //                     if ($finalHeaders && strpos($finalHeaders[0], '200') !== false) {
        //                         Log::info($finalUrl);
        //                         try {
        //                             $property->addMediaFromUrl($finalUrl)
        //                                     ->withCustomProperties([
        //                                         'title' => $property->name,
        //                                         'order' => null
        //                                     ])
        //                                     ->toMediaCollection('subImages', 'propertyFiles');
        //                                     $imageCount++; // Increment the counter after successfully adding an image
        //                         } catch (\Exception $e) {
        //                             Log::error("Error adding media from URL $finalUrl: " . $e->getMessage());
        //                         }
        //                     } else {
        //                         Log::warning("Final URL cannot be reached: $finalUrl");
        //                     }
        //                 }
        //             }
                    
        //             Log::info($Documents);
        //             // Log the whole $Documents array for debugging
        //             Log::info('Documents: ' . print_r($Documents, true));

        //             if (isset($Documents) && is_array($Documents) && array_key_exists('Document', $Documents)) {
        //                 $document = $Documents['Document']; // This is a single associative array, not an array of arrays

        //                 // Log the type and content of $document
        //                 Log::info('Document type: ' . gettype($document));
        //                 Log::info('Document content: ' . print_r($document, true));

        //                 // Check if $document is an array and has the 'Title' key
        //                 if (is_array($document) && isset($document['Title']) && $document['Title'] === 'QR Code') {
        //                     $qrCodeURL = $document['URL'];
        //                     Log::info('QR Code URL: ' . $qrCodeURL);
        //                 } else {
        //                     Log::warning('QR Code not found or Title is incorrect.');
        //                 }
        //             }

                    
        //             Log::info("QR LINK".$qrCodeURL);
        //             if($qrCodeURL){
        //                 $property->addMediaFromUrl($qrCodeURL)->toMediaCollection('qrs', 'propertyFiles' );
        //             }
        //             $property->save();
        //             $property->property_banner = $property->mainImage;
        //             $property->qr_link = $property->qr;
        //             $property->save();

        //             if (!empty($property->permit_number) && !empty($property->qr_link)) {
        //                 $property->is_valid = 1;
        //             } else {
        //                 $property->is_valid = 0; // Optionally set to false if not valid
        //             }
        //             $property->save();
                        
        //         //     $originalAttributes = $property->getOriginal();

        //         //     $originalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
        //         //     $originalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));

        //         //     if ($property->amenities) {
        //         //             $newPropertyOriginalAttributes['amenityIds'] = $property->amenities->pluck('id')->toArray();
        //         //     } else {
        //         //             $newPropertyOriginalAttributes['amenityIds'] = [];
        //         //     }

        //         // // Log activity for developer creation
        //         // $properties = json_encode([
        //         //     'old' => [],
        //         //     'new' => $originalAttributes,
        //         //     'updateAttribute' => [],
        //         //     'attribute' => []
        //         // ]);
        //         // logActivity('New Property has been created', $property->id, Property::class, $properties);
        //             Log::info('$property->website_status');
        //             Log::info($property->website_status);

        //         if ($property->website_status == config('constants.available') ) {

        //             $project = $property->project; // Assuming 'project' is the relationship name
        //             Log::info('$project->website_status'.$project->website_status. "is_VAlid". $property->is_valid);
        //             Log::info($project->website_status == config('constants.available') && $property->is_valid == 1);

        //             //$notValidProject = $property->where('is_valid', '!=', 1)->exists();

        //             if($project->website_status != config('constants.available')){

        //                 $property->status = config('constants.active');
        //                 $property->website_status = config('constants.requested');
        //                 $property->save();

        //             }elseif ($property->is_valid == 0) {
                        
        //                 $property->status = config('constants.inactive');
        //                 $property->website_status = config('constants.NA');
        //                 $property->save();


        //                 // $newPropertyOriginalAttributes = $property->getOriginal();

        //                 // if ($property->amenities) {
        //                 //     $newPropertyOriginalAttributes['amenityIds'] = $property->amenities->pluck('id')->toArray();
        //                 // } else {
        //                 //     $newPropertyOriginalAttributes['amenityIds'] = [];
        //                 // }


        //                 // if (isset($property->description)) {
        //                 //     $newPropertyOriginalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));
        //                 // }
        //                 // if (isset($property->short_description)) {
        //                 //     $newPropertyOriginalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
        //                 // }

        //                 // $properties = getUpdatedPropertiesForProperty($newPropertyOriginalAttributes, $originalAttributes);

        //                 // logActivity('Property marked as NA due to missing to Permit Number and QR ', $property->id, Property::class, $properties);
        //             }elseif($project->website_status == config('constants.available') && $property->is_valid == 1){
        //                 Log::info('invalid');
        //                 $property->status = config('constants.active');
        //                 $property->website_status = config('constants.available');
        //                 $property->save();
        //             }
        //         }

        //             Log::info("communityId=".$community->id);
        //             Log::info("projectName=".$project->title);
        //             Log::info("projectId=".$project->id);
        //             Log::info("propertyId=".$property->id);
        //         //}
        //         $counter++;
        //         }
        //     }
        //     DB::commit();
        //     Log::info('getRentListings End-' . Carbon::now());
        // }catch (\Exception $error) {
        //     $errorTrace = $error->getTraceAsString();
        //     $errorLine = $error->getLine();
        //     $errorFile = $error->getFile();
            
        //     $response = [
        //         'success' => false,
        //         'message' => $error->getMessage(),
        //         'error_trace' => $errorTrace,
        //         'error_file' => $errorFile,
        //         'error_line' => $errorLine,
        //     ];
        //     Log::info($response);
        //     //return response()->json($response, 500);
        // }

        GoyzerSaleProperties::dispatch();
        return "getSaleListings";
    }
    public function getRentListings()
    {


        Log::info('getRentListings');
        // DB::beginTransaction();
        // try{
        //     $today = Carbon::now();
            
        //     $user = User::where('email', 'goyzer@gmail.com')->first();
        //     $feed = 'https://webapi.goyzer.com/Company.asmx/RentListings?AccessCode=$R@nGe!NteRn@t!on@l&GroupCode=5084&PropertyType=&Bedrooms=&StartPriceRange=&EndPriceRange=&categoryID=&CountryID=&StateID=&CommunityID=&FloorAreaMin=&FloorAreaMax=&UnitCategory=&UnitID=&BedroomsMax=&PropertyID=&ReadyNow=&PageIndex=';
        //     $xml_arr  = simplexml_load_file($feed,'SimpleXMLElement',LIBXML_NOCDATA);        
        //     $xml_arr  = json_decode(json_encode($xml_arr,true),true);
    
            
        //     if (isset($xml_arr['UnitDTO']) && !empty($xml_arr['UnitDTO'])) {
        //         $properties = $xml_arr['UnitDTO'];


        //         $CRMProperties = Property::where('property_source', 'xml')->where('category_id', 9)->get();

        //         foreach ($CRMProperties as $prop) {
                    
        //             $flag = 0;
        //             foreach ($properties as $key => $value) {
        //                 if ($prop['reference_number'] == $value['RefNo']) {
        //                     $flag = 1;
        //                     break;
        //                 } else {
        //                     $flag = 0;
        //                 }
        //             }
        //             if ($flag == 0) {
        //                 $propDel = Property::where('id', '=', $prop['id'])->first();
        //                 $propDel->delete();
        //             }
        //         }

        //         $counter = 50;
        //         $limitedProperties = array_slice($properties, 50, 80);

        //         foreach($properties as $index=>$rental){
        //             if ($counter >= 80) {
        //                 break;  // Exit the loop after processing 26 elements
        //             }
        //             //if($rental['RefNo'] == 'AP7466'){

        //             Log::info($index);
        //            $qrCodeURL = NULL;

        //             $RefNo = isset($rental['RefNo']) ? $rental['RefNo'] : '';
                   
        //             $communityName = isset($rental['Community']) ? $rental['Community'] : '';
                   
        //             $accommodationName = isset($rental['Category']) ? $rental['Category'] : ''; 
        //             $projectName = isset($rental['PropertyName']) ? $rental['PropertyName'] : ''; 
        //             $BuiltupArea = isset($rental['BuiltupArea']) ? $rental['BuiltupArea'] : '';
        //             $PrimaryUnitView = isset($rental['PrimaryUnitView']) 
        //             ? (is_array($rental['PrimaryUnitView']) && empty($rental['PrimaryUnitView']) ? null : $rental['PrimaryUnitView'])
        //             : '';

        //             $SecondaryUnitView = isset($rental['SecondaryUnitView']) 
        //             ? (is_array($rental['SecondaryUnitView']) && empty($rental['SecondaryUnitView']) ? null : $rental['SecondaryUnitView'])
        //             : '';


        //             $SecondaryUnitView = isset($rental['SecondaryUnitView']) ? $rental['SecondaryUnitView'] : '';
        //             $HandoverDate = isset($rental['HandoverDate']) ? $rental['HandoverDate'] : '';
        //             if($HandoverDate){
        //                 $HandoverDate = date('Y-m-d', strtotime($HandoverDate));
        //             }
        //             $Agent = isset($rental['Agent']) ? $rental['Agent'] : '';
        //             $ContactNumber = isset($rental['ContactNumber']) ? $rental['ContactNumber'] : '';
        //             $StateName = isset($rental['StateName']) ? $rental['StateName'] : '';
        //             $Remarks = isset($rental['Remarks']) ? $rental['Remarks'] : '';
        //             $Remarks = str_replace(['<![CDATA[', ']]>'], '', $Remarks);

        //             $CountryName = isset($rental['CountryName']) ? $rental['CountryName'] : '';
        //             $CityName = isset($rental['CityName']) ? $rental['CityName'] : '';
        //             $DistrictName = isset($rental['DistrictName']) ? $rental['DistrictName'] : '';
        //             $Rent = isset($rental['Rent']) ? $rental['Rent'] : '';
        //             $ProGooglecoordinates = isset($rental['ProGooglecoordinates']) ? $rental['ProGooglecoordinates'] : '';
        //             $SalesmanEmail = isset($rental['SalesmanEmail']) ? $rental['SalesmanEmail'] : '';
        //             $MarketingTitle = isset($rental['MarketingTitle']) ? $rental['MarketingTitle'] : '';
        //             $MarketingOptions = isset($rental['MarketingOptions']) ? $rental['MarketingOptions'] : '';
        //             $Mandate = isset($rental['Mandate']) ? $rental['Mandate'] : '';
        //             $RentPerMonth = isset($rental['RentPerMonth']) ? $rental['RentPerMonth'] : '';
        //             $Rent = isset($rental['Rent']) ? $rental['Rent'] : '';
        //             $ReraStrNo = isset($rental['ReraStrNo']) ? $rental['ReraStrNo'] : '';
        //             //$PermitNumber = isset($rental['PermitNumber']) ? $rental['PermitNumber'] : '';

        //             $PermitNumber = isset($rental['PermitNumber']) 
        //             ? (is_array($rental['PermitNumber']) && empty($rental['PermitNumber']) ? null : $rental['PermitNumber'])
        //             : '';


        //             $Images = isset($rental['Images']) ? $rental['Images'] : '';
        //             $FittingFixtures = isset($rental['FittingFixtures']) ? $rental['FittingFixtures'] : '';
        //             $Furnish_status = isset($rental['Furnish_status']) ? $rental['Furnish_status'] : '';
        //             $Mandate = isset($rental['Mandate']) ? $rental['Mandate'] : '';
        //             $Documents = isset($rental['Documents']) ? $rental['Documents'] : '';

                    
        //             if($ProGooglecoordinates){
        //                 list($longitude, $latitude) = explode(',', $ProGooglecoordinates);
        //                 $latitude = trim($latitude);
        //                 $longitude = trim($longitude);
        //             }else{
        //                 $latitude = null;
        //                 $longitude = null;
        //             }
                   

        //             if($Mandate == 'Exclusive'){
        //                 $exclusive = 1;
        //             }else{
        //                 $exclusive = 0;
        //             }
        //             if($Furnish_status == 'Furnished'){
        //                 $is_furniture = 0;
        //             }elseif($Furnish_status == "Unfurnished"){
        //                 $is_furniture = 1;
        //             }else{
        //                 $is_furniture = 'partly';
        //             }
                   
        //             $NoOfBathrooms = isset($rental['NoOfBathrooms']) ? $rental['NoOfBathrooms'] : '';
        //             //$NoOfBathrooms = 2;
        //             if (is_array($NoOfBathrooms)) {
        //                 // If $Bedrooms is an array, take the first element
        //                 $NoOfBathrooms = !empty($NoOfBathrooms) ? $NoOfBathrooms[0] : '';
        //             } elseif (is_string($NoOfBathrooms)) {
        //                 // If $Bedrooms is already a string, use it directly
        //                 $NoOfBathrooms = $NoOfBathrooms;
        //             } else {
        //                 // Handle cases where $Bedrooms is neither an array nor a string
        //                 $NoOfBathrooms = '';
        //             }

                    

        //             $Bedrooms = isset($rental['Bedrooms']) ? $rental['Bedrooms'] : '';
        //             //$Bedrooms =2;
        //             if (is_array($Bedrooms)) {
        //                 // If $Bedrooms is an array, take the first element
        //                 $Bedrooms = !empty($Bedrooms) ? $Bedrooms[0] : '';
        //             } elseif (is_string($Bedrooms)) {
        //                 // If $Bedrooms is already a string, use it directly
        //                 $Bedrooms = $Bedrooms;
        //             } else {
        //                 // Handle cases where $Bedrooms is neither an array nor a string
        //                 $Bedrooms = '';
        //             }
        //             if($Bedrooms == 0 ){
        //                 $Bedrooms = 'Studio';
        //             }
                    
        //             if (Agent::where('email', $SalesmanEmail)->orWhere('secondary_email', $SalesmanEmail)->exists()) {
        //                 // Fetch existing agent
        //                 $agentD = Agent::where('email', $SalesmanEmail)
        //                                ->orWhere('secondary_email', $SalesmanEmail)
        //                                ->first();
        //             } else {
        //                 // Create a new agent
        //                 $agentD = new Agent;
        //                 $agentD->name = $Agent;
        //                 $agentD->email = $SalesmanEmail;
        //                 $agentD->status = 'Inactive';  // Correct variable name
        //                 $agentD->user_id = $user->id;
        //                 $agentD->save();
        //             }
                   
                    
        //             if(Accommodation::where('name', 'like', "%$accommodationName%")->exists()){
        //                 $propertyType = Accommodation::where('name', 'like', "%$accommodationName%")->first();
        //             }else{
        //                 $propertyType = new Accommodation;
        //                 $propertyType->name = $accommodationName;
        //                 $propertyType->user_id = $user->id;
        //                 $propertyType->save();
        //             }
        //             if(Community::where('name', 'like', "%$communityName%")->orWhere('name_1', 'like', "%$communityName%")->exists()){

        //                 $community = Community::where('name', 'like', "%$communityName%")->orWhere('name_1', 'like', "%$communityName%")->first();

        //             }else{
        //                 $community = new Community;
        //                 $community->name = $communityName;
        //                 $community->is_approved = config('constants.requested');
        //                 $community->status = config('constants.active');
        //                 $community->website_status = config('constants.requested');
        //                 $community->community_source = 'xml';

        //                 $community->address = $community->name. " ". $CityName. " ".$CountryName;

        //                 if($ProGooglecoordinates){
                           
        //                     $community->address_latitude = $latitude;
        //                     $community->address_longitude = $longitude;
                            
        //                 }

        //                 $community->user_id = $user->id;
        //                 $community->save();
                        
        //             }

                    
                   
        //             if(Project::where('title', 'like', "%$projectName%")->orWhere('title_1', 'like', "%$projectName%")->where('is_parent_project', 1)->exists()){
        //                 $project = Project::where('title', 'like', "%$projectName%")->orWhere('title_1', 'like', "%$projectName%")->where('is_parent_project', 1)->first();
        //             }else{
        //                 $project = new Project;
                      
        //                 $titleArray = explode(' ', $projectName);
        //                 $sub_title = $titleArray[0];

        //                 $subTitle1 = array_shift($titleArray);
        //                 $sub_title_1 = implode(" ",  $titleArray);
        //                 $project->title = $projectName;
        //                 $project->sub_title = $sub_title;
        //                 $project->sub_title_1 = $sub_title_1;
        //                 $project->is_approved = config('constants.requested');
        //                 $project->status = config('constants.active');
        //                 $project->website_status = config('constants.requested');
        //                 $project->project_source = 'xml';
        //                 $project->community_id = $community->id;
        //                 $project->accommodation_id = $propertyType->id;
        //                 $project->bathrooms = $Bedrooms;
        //                 $project->address = $community->name. " ". $CityName. " ".$CountryName;
        //                 //$project->completion_date = $HandoverDate;

        //                 if ($HandoverDate > $today) {
        //                     $project->completion_status_id = config('constants.underConstruction');
        //                 } else {
        //                     $project->completion_status_id = config('constants.completed');
        //                 }

        //                 if($ProGooglecoordinates){
        //                     $project->address_latitude = $latitude;
        //                     $project->address_longitude = $longitude;
        //                 }
                        
        //                 $project->user_id = $user->id;
        //                 $project->is_parent_project = 1; 
        //                 $project->save();
        //             }
        //             $subProjectTypeExist =  Project::where('is_parent_project', 0)->where('parent_project_id', $project->id)->where('list_type', config('constants.secondary'))->exists(); 
        //             if($subProjectTypeExist){
        //                 $subProjectTypeBedroomExist =  Project::where('is_parent_project', 0)
        //                                                 ->where('parent_project_id', $project->id)
        //                                                 ->where('list_type', config('constants.secondary'))
        //                                                 ->where('bedrooms', $Bedrooms)
        //                                                 ->exists();
                        
        //                 if($subProjectTypeBedroomExist){
        //                     $subProjectTypeBedroomAreaExist =  Project::where('is_parent_project', 0)
        //                                                 ->where('parent_project_id', $project->id)
        //                                                 ->where('list_type', config('constants.secondary'))
        //                                                 ->where('bedrooms', $Bedrooms)
        //                                                 ->where('area', $BuiltupArea)
        //                                                 ->exists();
        //                     if($subProjectTypeBedroomAreaExist){
                                
        //                         $subProject =  Project::where('is_parent_project', 0)
        //                                         ->where('parent_project_id', $project->id)
        //                                         ->where('list_type', config('constants.secondary'))
        //                                         ->where('bedrooms', $Bedrooms)
        //                                         ->where('area', $BuiltupArea)
        //                                         ->first();

        //                     }else{

        //                         $lastSubProject = Project::where('is_parent_project', 0)
        //                                         ->where('parent_project_id', $project->id)
        //                                         ->where('list_type', config('constants.secondary'))
        //                                         ->where('bedrooms', $Bedrooms)
        //                                         ->orderBy('created_at', 'desc') // Order by the 'created_at' column in descending order
        //                                         ->first(); // Use 'first()' to get the single record after ordering
                                
        //                         $titleParts = explode('-', $lastSubProject->title);

        //                         // Example usage
        //                         $part1 = $titleParts[0] ?? ''; // "1 BR"
        //                         $currentPart = $titleParts[1] ?? ''; // "A" (if it exists)

        //                         $nextPart = null;

        //                         if (ctype_alpha($currentPart) && strlen($currentPart) == 1) {
                                   
                                    
        //                             // Convert the current character to its ASCII value
        //                             $asciiValue = ord($currentPart);
                                
        //                             // Increment the ASCII value
        //                             $nextAsciiValue = $asciiValue + 1;
                                
        //                             // Convert the new ASCII value back to a character
        //                             $nextChar = chr($nextAsciiValue);
                                
        //                             // Ensure the incremented character is valid (i.e., within A-Z range)
        //                             if (ctype_alpha($nextChar) && strlen($nextChar) == 1) {
        //                                 $nextPart = $nextChar;
                                       
        //                             } else {
        //                                 Log::info("Incremented character is not a valid single letter.");
        //                             }
        //                         } else {
        //                             Log::info("Current part is not a single alphabetic character.". $currentPart);
        //                         }
                                
                                

        //                         $subProject = new Project;
        //                         if($Bedrooms == "Studio"){
        //                             $subProject->title = $Bedrooms." -".$nextPart;
        //                         }else{
        //                             $subProject->title = $Bedrooms." BR-".$nextPart;
        //                         }
                                
        //                         $subProject->is_parent_project = 0;
        //                         $subProject->parent_project_id = $project->id;
        //                         $subProject->bedrooms = $Bedrooms;
        //                         $subProject->list_type = config('constants.secondary');
        //                         $subProject->area =  $BuiltupArea;
        //                         $subProject->builtup_area =  $BuiltupArea;
        //                         $subProject->starting_price = $Rent;
        //                         $subProject->user_id = $user->id;
        //                         $subProject->accommodation_id = $propertyType->id;
        //                         $subProject->is_approved = config('constants.approved');
        //                         $subProject->status = config('constants.active');
        //                         $subProject->website_status = config('constants.available');
        //                         $subProject->save();

        //                     }
        //                 }else{

        //                     $subProject = new Project;
        //                     $subProject->title = $Bedrooms." BR-"."A";
        //                     $subProject->is_parent_project = 0;
        //                     $subProject->parent_project_id = $project->id;
        //                     $subProject->bedrooms = $Bedrooms;
        //                     $subProject->list_type = config('constants.secondary');
        //                     $subProject->area =  $BuiltupArea;
        //                     $subProject->builtup_area =  $BuiltupArea;
        //                     $subProject->starting_price = $Rent;
        //                     $subProject->user_id = $user->id;
        //                     $subProject->accommodation_id = $propertyType->id;
        //                     $subProject->is_approved = config('constants.approved');
        //                     $subProject->status = config('constants.active');
        //                     $subProject->website_status = config('constants.available');
        //                     $subProject->save();
        //                 }                                
        //             }else{
        //                 $subProject = new Project;
        //                 $subProject->title = $Bedrooms." BR-"."A";
        //                 $subProject->is_parent_project = 0;
        //                 $subProject->parent_project_id = $project->id;
        //                 $subProject->bedrooms = $Bedrooms;
        //                 $subProject->list_type = config('constants.secondary');
        //                 $subProject->area =  $BuiltupArea;
        //                 $subProject->builtup_area =  $BuiltupArea;
        //                 $subProject->starting_price = $Rent;
        //                 $subProject->user_id = $user->id;
        //                 $subProject->accommodation_id = $propertyType->id;
        //                 $subProject->is_approved = config('constants.approved');
        //                 $subProject->status = config('constants.active');
        //                 $subProject->website_status = config('constants.available');
        //                 $subProject->save();
        //             }

        //             Log::info('$RefNo'.$RefNo);

        //             if(Property::where('reference_number', $RefNo)->exists()){
        //                 $property = Property::where('reference_number', $RefNo)->first();
        //             }else{
        //                 $property = new Property();
        //             }

        //             if($RefNo == 'AP7592'){
        //                 Log::info('Error Start-');
        //                 Log::info('communityName-'.$communityName);
        //                 Log::info('projectName-'.$projectName);
        //                 Log::info('MarketingTitle-'.$MarketingTitle);
        //                 Log::info('is_furniture-'.$is_furniture);
        //                 Log::info('PermitNumber-'.$PermitNumber);
        //                 Log::info('Remarks-'.$Remarks);
        //                 Log::info('NoOfBathrooms-'.$NoOfBathrooms);
        //                 Log::info('Bedrooms-'.$Bedrooms);
        //                 Log::info('BuiltupArea-'.$BuiltupArea);
        //                 Log::info('exclusive-'.$exclusive);
        //                 Log::info('Rent Price-'.$Rent);
        //                 Log::info($PrimaryUnitView);
        //                 Log::info('RefNo-'.$RefNo);
        //                 Log::info('User ID-'.$user->id);
        //                 Log::info("agent id". $agentD->id);
        //                 Log::info(

        //                     "community id-".$community->id
                            

        //                 );
        //                 Log::info(

                            
        //                     "accommodation_id".$propertyType->id
                            
                            
        //                 );
        //                 Log::info(
        //                     "project_id".$project->id
                            
                            
        //                 );
        //                 Log::info(

        //                    "sub_project_id-".$subProject->id
                            
        //                 );

        //                 Log::info('Error End-');
        //             }
        //             $property->reference_number = $RefNo;
        //             $property->name = $MarketingTitle;
        //             $property->used_for = $propertyType->type;
        //             $property->sub_title = $MarketingTitle;
        //             $property->rental_period = 'Yearly';
        //             $property->is_furniture = $is_furniture;
        //             $property->emirate = $CityName;
        //             $property->permit_number = $PermitNumber;
        //             $property->short_description = $Remarks;
        //             $property->description = $Remarks;
        //             $property->bathrooms = $NoOfBathrooms;
        //             $property->bedrooms = $Bedrooms;
        //             $property->area = $BuiltupArea;
        //             $property->builtup_area = $BuiltupArea;
        //             $property->is_luxury_property = $exclusive;
        //             $property->price = $Rent;
        //             $property->is_feature = 0;
        //             $property->exclusive = $exclusive;
        //             $property->property_source = 'xml';
        //             $property->primary_view = $PrimaryUnitView;
        //             $property->is_display_home = 1;
        //             if($ProGooglecoordinates){
        //                 $property->address_latitude = $latitude;
        //                 $property->address_longitude = $longitude;   
        //             }
        //             $property->new_reference_number = $RefNo;
        //             $property->address = $community->name. " ". $CityName. " ".$CountryName;
        //             $property->user_id = $user->id;
        //             $property->agent_id = $agentD->id;
                
        //             $property->completion_status_id = 286;
        //             $property->community_id = $community->id;
        //             $property->accommodation_id = $propertyType->id;
        //             $property->project_id = $project->id;
        //             $property->sub_project_id = $subProject->id;
        //             $property->status = config('constants.active');
        //             $property->website_status = config('constants.available');
        //             $property->category_id = 9;
        //             $property->save();
        //             if ($property->category_id = 9) {
        //                 $prefix = 'S';
        //             } else {
        //                 $prefix = 'R';
        //             }
                
        
        //             if (!empty($property->permit_number) && !empty($property->qr_link)) {
        //                 $property->is_valid = 1;
        //             } else {
        //                 $property->is_valid = 0; // Optionally set to false if not valid
        //             }

        //             $property->save();
                    


        //             // amenities code start
                    
        //             if ($FittingFixtures && (count($FittingFixtures) > 0)) {
                      
                        
        //                 foreach ($FittingFixtures as $keys => $facilityArray) {

        //                     foreach($facilityArray as $faci){
        //                         $faci = $faci['Name'];
        //                         $checkFC = Amenity::where('name', $faci)->exists();
        //                         if($checkFC){
        //                             $checkFC = Amenity::where('name', $faci)->first();
        //                             $project->amenities()->attach($checkFC->id);
        //                             $property->amenities()->attach($checkFC->id);
        //                         }else{

        //                             $amenity = new Amenity;
        //                             $amenity->name = $faci;
        //                             $amenity->status = 'Inactive';
        //                             $amenity->is_approved = config('constants.requested');
        //                             $amenity->status = config('constants.Inactive');
        //                             $amenity->user_id   = $user->id;
                                    
        //                             $amenity->save();
        //                             $project->amenities()->attach($amenity->id);
        //                             $property->amenities()->attach($amenity->id);
        //                         }
        //                     }
                            
                            
                            
        //                 }
        //             }
                    
        //             // amenities code end
                   
        //             if (isset($Images) && is_array($Images) && array_key_exists('Image', $Images)) {
        //                 $Images = $Images['Image'];
        //                 // Take the first image as the main image
        //                 $mainImage = isset($Images[0]) ? $Images[0] : '';
        //                 // Take the rest of the images as gallery images
        //                 $galleryImages = array_slice($Images, 1);
        //                 //Log::info('galleryImages');
        //                 //Log::info($galleryImages);
        //             } else {
                       
        //                 $mainImage = '';
        //                 $galleryImages = [];
        //             }
        //             if ($mainImage) {
        //                 $property->addMediaFromUrl($mainImage['ImageURL'])->toMediaCollection('mainImages', 'propertyFiles');
        //             }
                    
        //             if ($galleryImages) {
        //                 $imageCount = 0; // Initialize the counter

        //                 foreach ($galleryImages as $key => $img) {

        //                     if ($imageCount >= 4) {
        //                         break; // Exit the loop if 4 images have been processed
        //                     }

        //                     $imageUrl = $img['ImageURL'];
                    

        //                     // Check if URL is reachable
        //                     $headers = @get_headers($imageUrl);
        //                     // Log::info('headers');
        //                     // Log::info($headers);
                    
        //                     // Follow redirect if necessary
        //                     $finalUrl = $imageUrl;
        //                     if (isset($headers[7])) {
        //                         $redirectUrl = $headers[7];
        //                         if (strpos($redirectUrl, 'Location:') === 0) {
        //                             $finalUrl = trim(substr($redirectUrl, 10));
        //                             Log::info('Redirected to: ' . $finalUrl);
        //                         }
        //                     }
                    
        //                     // Verify if the final URL is reachable
        //                      $finalHeaders = @get_headers($finalUrl);
        //                     // Log::info('Final headers');
        //                     // Log::info($finalHeaders);
                    
        //                     if ($finalHeaders && strpos($finalHeaders[0], '200') !== false) {
        //                         Log::info($finalUrl);
        //                         try {
        //                             $property->addMediaFromUrl($finalUrl)
        //                                     ->withCustomProperties([
        //                                         'title' => $property->name,
        //                                         'order' => null
        //                                     ])
        //                                     ->toMediaCollection('subImages', 'propertyFiles');
        //                                     $imageCount++; // Increment the counter after successfully adding an image
        //                         } catch (\Exception $e) {
        //                             Log::error("Error adding media from URL $finalUrl: " . $e->getMessage());
        //                         }
        //                     } else {
        //                         Log::warning("Final URL cannot be reached: $finalUrl");
        //                     }
        //                 }
        //             }
                        
        //             Log::info($Documents);
        //             // Log the whole $Documents array for debugging
        //             Log::info('Documents: ' . print_r($Documents, true));

        //             if (isset($Documents) && is_array($Documents) && array_key_exists('Document', $Documents)) {
        //                 $document = $Documents['Document']; // This is a single associative array, not an array of arrays

        //                 // Log the type and content of $document
        //                 Log::info('Document type: ' . gettype($document));
        //                 Log::info('Document content: ' . print_r($document, true));

        //                 // Check if $document is an array and has the 'Title' key
        //                 if (is_array($document) && isset($document['Title']) && $document['Title'] === 'QR Code') {
        //                     $qrCodeURL = $document['URL'];
        //                     Log::info('QR Code URL: ' . $qrCodeURL);
        //                 } else {
        //                     Log::warning('QR Code not found or Title is incorrect.');
        //                 }
        //             }

                    
        //             Log::info("QR LINK".$qrCodeURL);
        //             if($qrCodeURL){
        //                 $property->addMediaFromUrl($qrCodeURL)->toMediaCollection('qrs', 'propertyFiles' );
        //             }
        //             $property->save();
        //             $property->property_banner = $property->mainImage;
        //             $property->qr_link = $property->qr;
        //             $property->save();

        //             if (!empty($property->permit_number) && !empty($property->qr_link)) {
        //                 $property->is_valid = 1;
        //             } else {
        //                 $property->is_valid = 0; // Optionally set to false if not valid
        //             }
        //             $property->save();
                        
        //         //     $originalAttributes = $property->getOriginal();

        //         //     $originalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
        //         //     $originalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));

        //         //     if ($property->amenities) {
        //         //             $newPropertyOriginalAttributes['amenityIds'] = $property->amenities->pluck('id')->toArray();
        //         //     } else {
        //         //             $newPropertyOriginalAttributes['amenityIds'] = [];
        //         //     }

        //         // // Log activity for developer creation
        //         // $properties = json_encode([
        //         //     'old' => [],
        //         //     'new' => $originalAttributes,
        //         //     'updateAttribute' => [],
        //         //     'attribute' => []
        //         // ]);
        //         // logActivity('New Property has been created', $property->id, Property::class, $properties);
        //             Log::info('$property->website_status');
        //             Log::info($property->website_status);

        //         if ($property->website_status == config('constants.available') ) {

        //             $project = $property->project; // Assuming 'project' is the relationship name
        //             Log::info('Log::info($project->website_status'.$project->website_status. "is_VAlid". $property->is_valid);
        //             Log::info($project->website_status == config('constants.available') && $property->is_valid == 1);

        //             //$notValidProject = $property->where('is_valid', '!=', 1)->exists();

        //             if($project->website_status != config('constants.available')){

        //                 $property->status = config('constants.active');
        //                 $property->website_status = config('constants.requested');
        //                 $property->save();

        //             }elseif ($property->is_valid == 0) {
                        
        //                 $property->status = config('constants.inactive');
        //                 $property->website_status = config('constants.NA');
        //                 $property->save();


        //                 // $newPropertyOriginalAttributes = $property->getOriginal();

        //                 // if ($property->amenities) {
        //                 //     $newPropertyOriginalAttributes['amenityIds'] = $property->amenities->pluck('id')->toArray();
        //                 // } else {
        //                 //     $newPropertyOriginalAttributes['amenityIds'] = [];
        //                 // }


        //                 // if (isset($property->description)) {
        //                 //     $newPropertyOriginalAttributes['description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->description))));
        //                 // }
        //                 // if (isset($property->short_description)) {
        //                 //     $newPropertyOriginalAttributes['short_description'] = trim(strip_tags(str_replace('&#13;', '', trim($property->short_description))));
        //                 // }

        //                 // $properties = getUpdatedPropertiesForProperty($newPropertyOriginalAttributes, $originalAttributes);

        //                 // logActivity('Property marked as NA due to missing to Permit Number and QR ', $property->id, Property::class, $properties);
        //             }elseif($project->website_status == config('constants.available') && $property->is_valid == 1){
        //                 Log::info('invalid');
        //                 $property->status = config('constants.active');
        //                 $property->website_status = config('constants.available');
        //                 $property->save();
        //             }
        //         }

        //             Log::info("communityId=".$community->id);
        //             Log::info("projectName=".$project->title);
        //             Log::info("projectId=".$project->id);
        //             Log::info("propertyId=".$property->id);
        //         //}
        //         $counter++;
        //         }
        //     }
        //     DB::commit();
        //     Log::info('getRentListings End-' . Carbon::now());
        // }catch (\Exception $error) {
        //     $errorTrace = $error->getTraceAsString();
        //     $errorLine = $error->getLine();
        //     $errorFile = $error->getFile();
            
        //     $response = [
        //         'success' => false,
        //         'message' => $error->getMessage(),
        //         'error_trace' => $errorTrace,
        //         'error_file' => $errorFile,
        //         'error_line' => $errorLine,
        //     ];
        //     Log::info($response);
        //     //return response()->json($response, 500);
        // }


        
        GoyzerRentalProperties::dispatch();
        return "getRentListings";
        
        //     $baseUrl = 'https://webapi.goyzer.com';
        //     $endpoint = '/Company.asmx/RentListings';

        //     $queryParams = [
        //         'AccessCode' => env('API_ACCESS_CODE'),
        //         'GroupCode' => env('API_GROUP_CODE'),
        //         'PropertyType' => '',
        //         'Bedrooms' => '',
        //         'StartPriceRange' => '',
        //         'EndPriceRange' => '',
        //         'categoryID' => '',
        //         'CountryID' => '',
        //         'StateID' => '',
        //         'CommunityID' => '',
        //         'FloorAreaMin' => '',
        //         'FloorAreaMax' => '',
        //         'UnitCategory' => '',
        //         'UnitID' => '',
        //         'BedroomsMax' => '',
        //         'PropertyID' => '',
        //         'ReadyNow' => '',
        //         'PageIndex' => '',
        //     ];

        //     $response = Http::get($baseUrl . $endpoint, $queryParams);

        //     if ($response->successful()) {

        //          // Raw response body
        // $body = $response->body();
        // // JSON decoded response
        // $data = $body->json();
        // // Response headers
        // $headers = $response->headers();
        
        // dd( $data);
        //       //  dd($response->body());
        //         return $response->json();
        //     } else {
        //         return response()->json(['error' => 'Unable to fetch data'], 500);
        //     }
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
