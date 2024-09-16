<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\{
    Currency,
    Project,
    Community,
    WebsiteSetting,
    PageTag,
    Property,
    Developer,
    Accommodation,
    Category,
    Lead,
    Testimonial,
    Faq,
    PageContent,
    OtpVerification
};
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\{
    HomeCommunitiesResource,
    HomeTestimonial,
    HomeProjectResource,
    ProjectOptionResource,
    HomeMapProjectsResource,
    HomeMapProjectsCollectionResource,
    DubaiGuideResource,
    SellGuideResource,
    DeveloperListResource,
    BankListResource
};
use App\Jobs\{
    CRMLeadJob,
    LeadMovetoMortgageJob
};
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PDF;

class HomeController extends Controller
{
    
    public function sendSMS()
    {
        try {

            $url = "http://www.mshastra.com/sendurlcomma.aspx?user=rangeint&pwd=Range@23&senderid=Rangelnv&CountryCode=971&mobileno=586238697&msgtext=Hello";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$curl_scraped_page = curl_exec($ch);
curl_close($ch);
echo $curl_scraped_page;


            $url ="https://mshastra.com/sendurl.aspx?user=rangeint&pwd=Range@23&senderid=rangeint&mobileno=586238697&msgtext=HelloTR&CountryCode=+971";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $curl_scraped_page = curl_exec($ch);
            curl_close($ch);
            echo $curl_scraped_page;


            $message = "Hello OTP" . mt_rand(1000, 9999);
            $response = Http::get('https://www.mshastra.com/sendurl.aspx', [
                'user' => 'rangeint',
                'pwd' => 'Range@23',
                'senderid' => 'rangeint',
                'CountryCode' => '+971',
                'mobileno' => '586238697',
                'msgtext' =>  $message,
                'smstype' => '0'
            ]);
         
            if ($response->successful()) {
                $data = $response->json();
                return $this->success('SMS Data', $response, 200);
            } else {
                
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function bankNames()
    {
        try {

            $bankNames = [];
            $response = Http::get(config('app.mortgage_api_url') . 'banks');

            if ($response->successful()) {
                $responseData = $response->json(); // If expecting JSON response
                $bankNames = $responseData['data'];
            } else {
                $errorCode = $response->status();
                $errorMessage = $response->body(); // Get the error message
            }
            $bankNames = BankListResource::collection($bankNames);
            return $this->success('bankNames', $bankNames, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }

    public function mortageYears()
    {
        try {
            $numberOptions = [
                ["value" => "1", "label" => "1"],
                ["value" => "2", "label" => "2"],
                ["value" => "3", "label" => "3"],
                ["value" => "4", "label" => "4"],
                ["value" => "5", "label" => "5"],
                ["value" => "6", "label" => "6"],
                ["value" => "7", "label" => "7"],
                ["value" => "8", "label" => "8"],
                ["value" => "9", "label" => "9"],
                ["value" => "10", "label" => "10"],
                ["value" => "11", "label" => "11"],
                ["value" => "12", "label" => "12"],
                ["value" => "13", "label" => "13"],
                ["value" => "14", "label" => "14"],
                ["value" => "15", "label" => "15"],
                ["value" => "16", "label" => "16"],
                ["value" => "17", "label" => "17"],
                ["value" => "18", "label" => "18"],
                ["value" => "19", "label" => "19"],
                ["value" => "20", "label" => "20"],
                ["value" => "21", "label" => "21"],
                ["value" => "22", "label" => "22"],
                ["value" => "23", "label" => "23"],
                ["value" => "24", "label" => "24"],
                ["value" => "25", "label" => "25"],
            ];


            return $this->success('mortgageYears', $numberOptions, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function dubaiGuideData()
    {
        try {
            $data = PageContent::WherePageName(config('constants.dubaiGuide.name'))->first();
            return $this->success('dubai Guide Data', new DubaiGuideResource($data), 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function sellerGuideData()
    {
        try {
            $data = PageContent::WherePageName(config('constants.sellerGuide.name'))->first();
            return $this->success('seller Data', new SellGuideResource($data), 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    // Function to convert number to words
    public function convertToWords($number)
    {
        // Define arrays for number names
        $ones = array(
            0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen'
        );
        $tens = array(
            0 => 'zero', 1 => 'ten', 2 => 'twenty', 3 => 'thirty', 4 => 'forty', 5 => 'fifty', 6 => 'sixty', 7 => 'seventy', 8 => 'eighty', 9 => 'ninety'
        );

        // If the number is less than 20, return its name directly from $ones array
        if ($number < 20) {
            return $ones[$number];
        }

        // If the number is greater than or equal to 100 crore, process crore part
        if ($number >= 10000000) {
            $crore = floor($number / 10000000);
            $remaining = $number % 10000000;
            $croreStr = $this->convertToWords($crore) . ' crore ';
            return $croreStr;
        }

        // If the number is greater than or equal to 1 million, process million part
        if ($number >= 1000000) {
            $million = floor($number / 1000000);
            $remaining = $number % 1000000;
            $millionStr = $this->convertToWords($million) . ' million ';
            return $millionStr;
        }

        // If the number is greater than or equal to 1 lakh, process lakh part
        if ($number >= 100000) {
            $lakh = floor($number / 100000);
            $remaining = $number % 100000;
            $lakhStr = $this->convertToWords($lakh) . ' lakh ';
            return $lakhStr;
        }

        // If the number is greater than or equal to 1 thousand, process thousand part
        if ($number >= 1000) {
            $thousand = floor($number / 1000);
            $remaining = $number % 1000;
            $thousandStr = $this->convertToWords($thousand) . ' thousand ';
            return $thousandStr;
        }

        // If the number is greater than 100, process hundred part
        if ($number >= 100) {
            $hundred = floor($number / 100);
            $remaining = $number % 100;
            $hundredStr = $this->convertToWords($hundred) . ' hundred ';
            return $hundredStr;
        }

        // If the number is greater than 20 and not a multiple of 10, process tens and ones parts
        $ten = floor($number / 10);
        $one = $number % 10;
        return $tens[$ten] . ' ' . $ones[$one];
    }


    // Function to convert words to number
    public function convertToNumber($word)
    {
        // Define arrays for number names
        $ones = array(
            'zero' => 0, 'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5, 'six' => 6, 'seven' => 7, 'eight' => 8, 'nine' => 9,
            'ten' => 10, 'eleven' => 11, 'twelve' => 12, 'thirteen' => 13, 'fourteen' => 14, 'fifteen' => 15, 'sixteen' => 16, 'seventeen' => 17, 'eighteen' => 18, 'nineteen' => 19,
            'twenty' => 20, 'thirty' => 30, 'forty' => 40, 'fifty' => 50, 'sixty' => 60, 'seventy' => 70, 'eighty' => 80, 'ninety' => 90
        );

        // Split the word by spaces and hyphens
        $words = preg_split('/[\s-]+/', $word);

        $total = 0;
        $current = 0;

        foreach ($words as $word) {
            if (isset($ones[$word])) {
                $current += $ones[$word];
            } elseif ($word == 'hundred') {
                $current *= 100;
            } elseif ($word == 'thousand') {
                $total += $current * 1000;
                $current = 0;
            } elseif ($word == 'lakh') {
                $total += $current * 100000;
                $current = 0;
            } elseif ($word == 'crore') {
                $total += $current * 10000000;
                $current = 0;
            } elseif ($word == 'million') {
                $total += $current * 1000000;
                $current = 0;
            } elseif ($word == 'billion') {
                $total += $current * 1000000000;
                $current = 0;
            }
        }

        $total += $current;

        return $total;
    }


    public function homeDataCache()
    {
        try {
            // $communities = HomeCommunitiesResource::collection(Community::active()->approved()->home()->limit(12)->orderByRaw('ISNULL(communityOrder)')->orderBy('communityOrder', 'asc')->get() );
            $communities = Cache::remember('homeCommunities', 24 * 60 * 60, function () {
                return  HomeCommunitiesResource::collection(DB::table('communities')
                    ->select('name', 'slug', 'banner_image', 'id')
                    ->where('status', config('constants.active'))
                    ->where('is_approved', config('constants.approved'))
                    ->where('display_on_home', 1)
                    ->whereNull('deleted_at')
                    ->limit(12)
                    ->orderByRaw('ISNULL(communityOrder)')
                    ->orderBy('communityOrder', 'asc')
                    ->get());
            });



            //$testimonials =  HomeTestimonial::collection(Testimonial::select()->active()->latest()->get());
            $testimonials =  Cache::remember('homeTestimonials', 24 * 60 * 60, function () {
                return HomeTestimonial::collection(DB::table('testimonials')
                    ->select('id', 'feedback', 'client_name', 'rating')
                    ->where('status', config('constants.active'))
                    ->whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')
                    ->get());
            });

            //$developers = DeveloperListResource::collection(Developer::active()->approved()->home()->orderByRaw('ISNULL(developerOrder)')->orderBy('developerOrder', 'asc')->get());

            $developers = Cache::remember('homeDevelopers', 24 * 60 * 60, function () {
                return DeveloperListResource::collection(DB::table('developers')
                    ->select('id', 'logo_image', 'slug', 'name', 'developerOrder')
                    ->where('status', config('constants.active'))
                    ->where('is_approved', config('constants.approved'))
                    ->where('display_on_home', 1)
                    ->whereNull('deleted_at')
                    ->orderByRaw('ISNULL(developerOrder)')
                    ->orderBy('developerOrder', 'asc')
                    ->get());
            });
            //$allProjects = Project::with(['accommodation', 'subProjects', 'completionStatus'])->mainProject()->approved()->active()->home();
            if (Cache::has('homeProjects')) {
                $data = Cache::get('homeProjects');

                $projects = $data['projects'];
                $newProjects = $data['newProjects'];
                $mapProjects = $data['mapProjects'];
            } else {
                $allProjects =  DB::table('projects')
                    ->select(
                        'projects.id',
                        'projects.title',
                        'projects.slug',
                        'projects.banner_image',
                        'projects.projectOrder',
                        'projects.address',
                        'projects.completion_date',
                        'projects.address_latitude',
                        'projects.address_longitude',
                        'accommodations.name as accommodation_name',
                        'completion_statuses.name as completion_statuses_name'
                    )
                    ->leftJoin('accommodations', 'projects.accommodation_id', '=', 'accommodations.id')
                    ->leftJoin('completion_statuses', 'projects.completion_status_id', '=', 'completion_statuses.id')
                    ->where('projects.is_parent_project', true)
                    ->where('projects.is_approved', config('constants.approved'))
                    ->where('projects.status', config('constants.active'))
                    ->where('projects.is_display_home', 1)
                    ->whereNull('projects.deleted_at');

                $displayProjects = clone $allProjects;
                $displayProjects = $displayProjects->orderByRaw('ISNULL(projects.projectOrder)')->orderBy('projects.projectOrder', 'asc')->take(8);

                $projects = HomeProjectResource::collection($displayProjects->get());

                $newProjects = ProjectOptionResource::collection($allProjects->OrderBy('projects.title', 'asc')->get());

                // Retrieve sub-projects using a recursive Common Table Expression (CTE)
                $subProjects = DB::table('projects')
                    ->select('projects.id as sub_project_id', 'projects.area', 'projects.bedrooms', 'projects.starting_price', 'projects.parent_project_id')
                    ->where('projects.is_parent_project', false) // Fetch only sub-projects
                    ->where('projects.is_approved', config('constants.approved'))
                    ->where('projects.status', config('constants.active'))
                    ->whereNull('projects.deleted_at')
                    ->get();

                $allProjectsResult = $allProjects->get();
                $projectsWithSubProjects = [];

                foreach ($allProjectsResult as $project) {
                    $projectsWithSubProjects[$project->id] = (array) $project;
                    $projectsWithSubProjects[$project->id]['sub_projects'] = $subProjects->where('parent_project_id', $project->id)->values()->all();
                    $projectsWithSubProjects[$project->id]['has_sub_projects'] = count($projectsWithSubProjects[$project->id]['sub_projects']) > 0;
                }

                $mapProjects = HomeMapProjectsResource::collection($projectsWithSubProjects);

                Cache::put('homeProjects', [
                    'projects' => $projects,
                    'newProjects' => $newProjects,
                    'mapProjects' => $mapProjects
                ], 24 * 60 * 60); // 24 hours
            }
            if (Cache::has('homeProjectsFormattedPrice')) {
                $formattedNumbers = Cache::get('homeProjectsFormattedPrice');
            } else {

                // Fetch results from the database
                $results = DB::select("
                        SELECT starting_price
                        FROM projects
                        WHERE deleted_at IS NULL
                        AND status = 'active'
                        AND is_approved = 'approved'
                        AND starting_price IS NOT NULL
                        AND starting_price REGEXP '^[0-9]+$'
                        GROUP BY starting_price
                        ORDER BY starting_price;
                    ");

                // Initialize arrays to store starting prices in words
                $thousands = [];
                $lakhs = [];
                $crores = [];
                $millions = [];
                $billions = [];

                // Convert each starting price to words and categorize them
                foreach ($results as $row) {
                    $startingPrice = (int)$row->starting_price;
                    if ($startingPrice >= 1000000000) {
                        $billions[] = $this->convertToWords($startingPrice);
                    } elseif ($startingPrice >= 10000000) {
                        $crores[] = $this->convertToWords($startingPrice);
                    } elseif ($startingPrice >= 100000) {
                        $lakhs[] = $this->convertToWords($startingPrice);
                    } elseif ($startingPrice >= 1000) {
                        $thousands[] = $this->convertToWords($startingPrice);
                    } elseif ($startingPrice >= 1000000) {
                        $millions[] = $this->convertToWords($startingPrice);
                    }
                }

                $combinedArray = array_merge(array_unique($thousands), array_unique($lakhs), array_unique($crores), array_unique($millions), array_unique($billions));

                $formattedNumbers = [];
                foreach ($combinedArray as $row) {
                    $formattedNumbers[] =  $this->convertToNumber($row);
                }
                $formattedNumbers = sort($formattedNumbers);

                Cache::put('homeProjectsFormattedPrice', $formattedNumbers, 24 * 60 * 60); // 24 hours
            }

            $data = [
                'formattedNumbers' => $formattedNumbers,
                'projects' => $projects,
                'newProjects' => $newProjects,
                'mapProjects' => $mapProjects,
                'communities' => $communities,
                'testimonials' => $testimonials,
                'developers' => $developers,
            ];

            return $this->success('Home Data', $data, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }

    public function homeData()
    {
        try {
          
            return $this->success('Home Data', Cache::remember('homeData123', 3 * 60 * 60, function () {
                // $communities = HomeCommunitiesResource::collection(Community::active()->approved()->home()->limit(12)->orderByRaw('ISNULL(communityOrder)')->orderBy('communityOrder', 'asc')->get() );
                $communities =  HomeCommunitiesResource::collection(DB::table('communities')
                    ->select('name', 'slug', 'banner_image', 'id')
                    //->where('status', config('constants.active'))
                    ->where('website_status', config('constants.available'))
                    ->where('display_on_home', 1)
                    ->whereNull('deleted_at')
                    ->limit(12)
                    ->orderByRaw('ISNULL(communityOrder)')
                    ->orderBy('communityOrder', 'asc')
                    ->get());




                //$testimonials =  HomeTestimonial::collection(Testimonial::select()->active()->latest()->get());
                $testimonials =  HomeTestimonial::collection(DB::table('testimonials')
                    ->select('id', 'feedback', 'client_name', 'rating')
                    ->where('status', config('constants.active'))
                    ->whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')
                    ->get());


                //$developers = DeveloperListResource::collection(Developer::active()->approved()->home()->orderByRaw('ISNULL(developerOrder)')->orderBy('developerOrder', 'asc')->get());

                $developers =  DeveloperListResource::collection(DB::table('developers')
                    ->select('id', 'logo_image', 'slug', 'name', 'developerOrder')
                    ->where('website_status', config('constants.available'))
                    ->where('display_on_home', 1)
                   
                    ->whereNull('deleted_at')
                    ->orderByRaw('ISNULL(developerOrder)')
                    ->orderBy('developerOrder', 'asc')
                    ->get());

                //$allProjects = Project::with(['accommodation', 'subProjects', 'completionStatus'])->mainProject()->approved()->active()->home();

                $allProjects =  DB::table('projects')
                    ->select(
                        'projects.id',
                        'projects.title',
                        'projects.slug',
                        'projects.banner_image',
                        'projects.projectOrder',
                        'projects.address',
                        'projects.completion_date',
                        'projects.address_latitude',
                        'projects.address_longitude',
                        'accommodations.name as accommodation_name',
                        'completion_statuses.name as completion_statuses_name'
                    )
                    ->leftJoin('accommodations', 'projects.accommodation_id', '=', 'accommodations.id')
                    ->leftJoin('completion_statuses', 'projects.completion_status_id', '=', 'completion_statuses.id')
                    ->where('projects.is_parent_project', true)
                   // ->where('projects.is_approved', config('constants.approved'))
                    ->where('projects.website_status', config('constants.available'))
                    ->where('projects.is_display_home', 1)
                    ->whereNull('projects.deleted_at');

                $displayProjects = clone $allProjects;
                $displayProjects = $displayProjects->orderByRaw('ISNULL(projects.projectOrder)')->orderBy('projects.projectOrder', 'asc')->take(8);

                $projects = HomeProjectResource::collection($displayProjects->get());

                $newProjects = ProjectOptionResource::collection($allProjects->OrderBy('projects.title', 'asc')->get());

                // Retrieve sub-projects using a recursive Common Table Expression (CTE)
                $subProjects = DB::table('projects')
                    ->select('projects.id as sub_project_id', 'projects.area', 'projects.bedrooms', 'projects.starting_price', 'projects.parent_project_id')
                    ->where('projects.is_parent_project', false) // Fetch only sub-projects
                    // ->where('projects.is_approved', config('constants.approved'))
                    // ->where('projects.status', config('constants.active'))
                    ->whereNull('projects.deleted_at')
                    ->get();

                $allProjectsResult = $allProjects->get();
                $projectsWithSubProjects = [];

                foreach ($allProjectsResult as $project) {
                    $projectsWithSubProjects[$project->id] = (array) $project;
                    $projectsWithSubProjects[$project->id]['sub_projects'] = $subProjects->where('parent_project_id', $project->id)->values()->all();
                    $projectsWithSubProjects[$project->id]['has_sub_projects'] = count($projectsWithSubProjects[$project->id]['sub_projects']) > 0;
                }

                //$mapProjects = HomeMapProjectsResource::collection($projectsWithSubProjects);

                $currencyINR = null;
                if (WebsiteSetting::where('key', config('constants.INR_Currency'))->exists()) {
                    $currencyINR = WebsiteSetting::getSetting(config('constants.INR_Currency')) ? WebsiteSetting::getSetting(config('constants.INR_Currency')) : '';
                }

                $mapProjects = new HomeMapProjectsCollectionResource($projectsWithSubProjects, $currencyINR);

                
                $results = DB::select("
                SELECT starting_price
                FROM projects
                WHERE deleted_at IS NULL
               
                AND website_status = 'available'
                AND starting_price IS NOT NULL
                AND starting_price REGEXP '^[0-9]+$'
                GROUP BY starting_price
                ORDER BY starting_price;
            ");

            // Initialize arrays to store starting prices in words
            $thousands = [];
            $lakhs = [];
            $crores = [];
            $millions = [];
            $billions = [];

            // Convert each starting price to words and categorize them
            foreach ($results as $row) {
                $startingPrice = (int)$row->starting_price;
                if ($startingPrice >= 1000000000) {
                    $billions[] = $this->convertToWords($startingPrice);
                } elseif ($startingPrice >= 10000000) {
                    $crores[] = $this->convertToWords($startingPrice);
                } elseif ($startingPrice >= 100000) {
                    $lakhs[] = $this->convertToWords($startingPrice);
                } elseif ($startingPrice >= 1000) {
                    $thousands[] = $this->convertToWords($startingPrice);
                } elseif ($startingPrice >= 1000000) {
                    $millions[] = $this->convertToWords($startingPrice);
                }
            }

            $combinedArray = array_merge(array_unique($thousands), array_unique($lakhs), array_unique($crores), array_unique($millions), array_unique($billions));

            $text = [];
            foreach ($combinedArray as $row) {
                $text[] =  $this->convertToNumber($row);
            }
            sort($text);

                
                
                return $data = [
                    'formattedNumbers' => $text,
                    'projects' => $projects,
                    'newProjects' => $newProjects,
                    'mapProjects' => $mapProjects,
                    'communities' => $communities,
                    'testimonials' => $testimonials,
                    'developers' => $developers,
                ];
            }), 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }

    public function getHomeData()
    {
        try {
            $communities = Community::active()->approved()->home()->latest()->limit(8)->get()->map(function ($community) {
                return [
                    'id' => 'community_' . $community->id,
                    'name' => $community->name,
                    'slug' => $community->slug,
                    'mainImage' => $community->mainImage,
                ];
            });
            $projects = Project::with(['accommodation'])->select('id', 'title', 'slug')->mainProject()->approved()->active()->home()->limit(8)->latest()->get()->map(function ($project) {
                return [
                    'id' => 'project_' . $project->id,
                    'title' => $project->title,
                    'slug' => $project->slug,
                    'mainImage' => $project->mainImage,
                    'accommodation' => $project->accommodation > 0 ? $project->accommodation->name : ''
                ];
            });
            $newProjects = Project::select('id', 'title', 'slug')->mainProject()->home()->approved()->active()->latest()->get()->map(function ($project) {
                return [
                    'id' => 'newProject_' . $project->id,
                    'value' => $project->slug,
                    'label' => $project->title,
                ];
            });
            $mapProjects = Project::mainProject()->approved()->home()->active()->latest()->get();

            foreach ($mapProjects as $key => $value) {
                $minBed = $value->subProjects->min('bedrooms');
                $maxBed = $value->subProjects->max('bedrooms');
                if ($minBed != $maxBed) {
                    $bedroom = $minBed . "-" . $maxBed;
                } else {
                    $bedroom = $minBed;
                }
                $value->setAttribute('lat', (float)$value->address_latitude);
                $value->setAttribute('lng', (float)$value->address_longitude);
                $value->setAttribute('accommodationName', $value->accommodation ? $value->accommodation->name : null);
                $value->setAttribute('completionStatusName', $value->completionStatus ? $value->completionStatus->name : null);
                $value->setAttribute('starting_price', $value->subProjects->min('starting_price'));
                $value->setAttribute('bedrooms', $bedroom);
                $value->setAttribute('area', $value->subProjects->min('area'));
            }


            $mapProjects = $mapProjects->toJson();

            $testimonials =  Testimonial::select()->active()->latest()->get()->map(function ($testimonial) {
                return [
                    'id' => 'testimonal_' . $testimonial->id,
                    'clientName' => $testimonial->client_name,
                    'feedback' => $testimonial->feedback,
                    'star' => $testimonial->rating
                ];
            });

            // $mapProjects = Project::with('accommodations')->mainProject()->active()->latest()->get()->map(function ($project) {
            //                   return [
            //                     'id' => 'project_'.$project->id,
            //                     'title' => $project->title,
            //                     'slug' => $project->slug,
            //                     'area'=> $project->area,
            //                     'mainImage' => $project->mainImage,
            //                     'bedrooms'=>$project->bedrooms,
            //                     'bathrooms'=>$project->bathrooms,
            //                     'address'=> $project->address,
            //                     'lat'=>(double)$project->address_latitude,
            //                     'lng'=> (double)$project->address_longitude,
            //                     'price'=>$project->starting_price,
            //                     'accommodation'=>$project->accommodations()->count() > 0 ? $project->accommodations[0]->name :''
            //                   ];
            //                 });

            // $mapProjects = $mapProjects->toJson();
            //$mapProjects = $mapProjects;

            $pagemeta =  PageTag::where('page_name', 'home')->first();
            if ($pagemeta) {
                $title = $pagemeta->meta_title;
                $description = $pagemeta->meta_description;
                $keywords = $pagemeta->meta_keywords;
            } else {
                $title = WebsiteSetting::getSetting('website_name') ? WebsiteSetting::getSetting('website_name') : '';
                $description = WebsiteSetting::getSetting('website_description') ? WebsiteSetting::getSetting('website_description') : '';
                $keywords = WebsiteSetting::getSetting('website_keyword') ? WebsiteSetting::getSetting('website_keyword') : '';
            }
            $data = [
                'title' => $title,
                'pageDescription' => $description,
                'pageKeyword' => $keywords,
                'communities' => $communities,
                'projects' => $projects,
                'newProjects' => $newProjects,
                'testimonials' => $testimonials,
                'mapProjects' => $mapProjects
            ];

            return $this->success('Home Data', $data, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function faqs()
    {
        try {
            $faqs =  Faq::OrderBy('OrderBy', 'asc')->active()->get()->map(function ($faq) {
                return [
                    'id' => "faq_" . $faq->id,
                    'question' => $faq->question,
                    'answer' => $faq->long_answer->render()
                ];
            });
            return $this->success('FAQ Data', $faqs, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $keyword = $request->keyword;
            $developers = Developer::Where('name', 'like', "%$keyword%")->approved()->active()->get()->map(function ($developer) {
                return [
                    'id' => $developer->id,
                    'slug' => $developer->slug,
                    'name' => $developer->name,
                    'type' => 'developers/' . $developer->slug,
                ];
            })->toArray();

            $communities = Community::Where('name', 'like', "%$keyword%")->approved()->active()->get()->map(function ($community) {
                return [
                    'id' => $community->id,
                    'slug' => $community->slug,
                    'name' => $community->name,
                    'type' => 'communities/' . $community->slug,
                ];
            })->toArray();
            $projects = Project::Where('title', 'like', "%$keyword%")->mainProject()->approved()->active()->get()->map(function ($project) {
                return [
                    'id' => $project->id,
                    'slug' => $project->slug,
                    'name' => $project->title,
                    'type' => 'projects/' . $project->slug,
                ];
            })->toArray();
            $properties = Property::Where('name', 'like', "%$keyword%")->approved()->active()->get()->map(function ($property) {
                return [
                    'id' => $property->id,
                    'slug' => $property->slug,
                    'name' => $property->name,
                    'type' => 'properties/' . $property->slug,
                ];
            })->toArray();

            // $pages = [
            //         'id' => "faq",
            //         'slug' => "/faqs",
            //         'name' => "FAQ",
            //         'type'=> "faqs",
            // ];


            $suggestions = array();

            $suggestions =  array_merge($suggestions, $developers, $communities, $projects, $properties);

            return $this->success('Home Suggestion', $suggestions, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }

    public function searchR(Request $request)
    {
        try {
            $keyword = $request->keyword;
            $developers = Developer::select('id', 'slug', 'name')->Where('name', 'like', "%$keyword%")->approved()->active()->get()->map(function ($developer) {
                return [
                    'id' => $developer->id,
                    'slug' => $developer->slug,
                    'name' => $developer->name,
                    'type' => 'properties?developer_name=' . $developer->name . '&developer_detail=developer-' . $developer->id,
                ];
            })->toArray();

            $communities = Community::select('id', 'slug', 'name', 'emirates')->Where('name', 'like', "%$keyword%")->approved()->active()->get()->map(function ($community) {
                return [
                    'id' => $community->id,
                    'slug' => $community->slug,
                    'name' => $community->name . "(" . $community->emirates . ")",
                    'type' => 'properties?community_name=' . $community->name . "(" . $community->emirates . ")" . '&community_detail=&community-' . $community->id,
                ];
            })->toArray();

            $projects = Project::select('id', 'slug', 'title', 'community_id')->with('mainCommunity')->Where('title', 'like', "%$keyword%")->mainProject()->approved()->active()->get()->map(function ($project) {
                return [
                    'id' => $project->id,
                    'slug' => $project->slug,
                    'name' => $project->title . "(" . $project->mainCommunity->name . ", " . $project->mainCommunity->emirates . ")",
                    'type' => 'properties?project_name=' . $project->title . '&project_detail=project-' . $project->id,
                ];
            })->toArray();
            // $properties = Property::Where('name','like', "%$keyword%")->approved()->active()->get()->map(function($property){
            //     return [
            //         'id' => $property->id,
            //         'slug' => $property->slug,
            //         'name' => $property->name,
            //         'type'=> 'properties/'.$property->slug,
            //     ];
            // })->toArray();
            $suggestions = array();

            $suggestions =  array_merge($suggestions, $developers, $communities, $projects);

            return $this->success('Home Suggestion', $suggestions, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }

    public function searchCount(Request $request)
    {
        try {
            $keyword = $request->keyword;
            $developers = array();
            $communities = array();
            $projects = array();
            $properties = array();
            $suggestions = array();
            $singleDeveloper = Developer::select('id', 'slug', 'name')->Where('name', $keyword)->approved()->active()->first();
            if ($singleDeveloper) {

                array_push($suggestions, [
                    'id' => $singleDeveloper->id,
                    'slug' => $singleDeveloper->slug,
                    'name' => $singleDeveloper->name,
                    'type' => 'developers/' . $singleDeveloper->slug,
                ]);

                $projectIds = Project::where('developer_id', $singleDeveloper->id)->active()->mainProject()->approved()->latest()->pluck('id');

                $propertyIds = Property::whereIn('project_id', $projectIds)->active()->approved()->latest()->pluck('id');

                if ($singleDeveloper->communities()->active()->approved()->count() > 0) {
                    array_push($suggestions, [
                        'id' => $singleDeveloper->id,
                        'slug' => $singleDeveloper->slug,
                        'name' => $singleDeveloper->communities()->active()->approved()->count() . " Communities develop by " . $singleDeveloper->name,
                        'type' => 'communities?developer_name=' . $singleDeveloper->name . '&developer_detail=' . $singleDeveloper->id,
                    ]);
                }
                if (count($projectIds) > 0) {
                    array_push($suggestions, [
                        'id' => $singleDeveloper->id,
                        'slug' => $singleDeveloper->slug,
                        'name' => count($projectIds) . " Projects in " . $singleDeveloper->name,
                        'type' => 'projects?developer_name=' . $singleDeveloper->name . '&developer_detail=developer-' . $singleDeveloper->id,
                    ]);
                }

                if (count($propertyIds) > 0) {
                    array_push($suggestions, [
                        'id' => $singleDeveloper->id,
                        'slug' => $singleDeveloper->slug,
                        'name' => count($propertyIds) . " Properties in " . $singleDeveloper->name,
                        'type' => 'properties?developer_name=' . $singleDeveloper->name . '&developer_detail=developer-' . $singleDeveloper->id,
                    ]);
                }

                // $suggestions =  array_merge($developers, $communities, $projects);

                return $this->success('Home Suggestion', $suggestions, 200);
            }

            $singleCommunity = Community::select('id', 'slug', 'name')->Where('name', $keyword)->approved()->active()->first();
            if ($singleCommunity) {
                array_push($suggestions, [
                    'id' => $singleCommunity->id,
                    'slug' => $singleCommunity->slug,
                    'name' => $singleCommunity->name,
                    'type' => 'communities/' . $singleCommunity->slug,
                ]);

                $developerIds = $singleCommunity->developers()->active()->approved()->count();
                $projectIds = Project::where('community_id', $singleCommunity->id)->active()->mainProject()->approved()->latest()->pluck('id');
                $propertyIds = Property::whereIn('project_id', $projectIds)->active()->approved()->latest()->pluck('id');

                if ($developerIds > 0) {
                    array_push($suggestions, [
                        'id' => $singleCommunity->id,
                        'slug' => $singleCommunity->slug,
                        'name' => $developerIds . " developers in " . $singleCommunity->name,
                        'type' => 'developers?community_name=' . $singleCommunity->name . '&community_detail=' . $singleCommunity->id,
                    ]);
                }
                if (count($projectIds) > 0) {
                    array_push($suggestions, [
                        'id' => $singleCommunity->id,
                        'slug' => $singleCommunity->slug,
                        'name' => count($projectIds) . " Projects in " . $singleCommunity->name,
                        'type' => 'projects?community_name=' . $singleCommunity->name . '&community_detail=community-' . $singleCommunity->id,
                    ]);
                }

                if (count($propertyIds) > 0) {
                    array_push($suggestions, [
                        'id' => $singleCommunity->id,
                        'slug' => $singleCommunity->slug,
                        'name' => count($propertyIds) . " Properties in " . $singleCommunity->name,
                        'type' => 'properties?community_name=' . $singleCommunity->name . '&community_detail=community-' . $singleCommunity->id,
                    ]);
                }

                return $this->success('Home Suggestion', $suggestions, 200);
            }

            $singleProject = Project::select('id', 'slug', 'title', 'community_id')->with('mainCommunity')->Where('title', $keyword)->mainProject()->approved()->active()->first();

            if ($singleProject) {


                $propertyIds = Property::where('project_id', $singleProject->id)->active()->approved()->pluck('id');

                array_push($suggestions, [
                    'id' => $singleProject->id,
                    'slug' => $singleProject->slug,
                    'name' => $singleProject->title,
                    'type' => 'projects/' . $singleProject->slug,
                ]);

                if (count($propertyIds) > 0) {
                    array_push($suggestions, [
                        'id' => $singleProject->id,
                        'slug' => $singleProject->slug,
                        'name' => count($propertyIds) . " Properties in " . $singleProject->title,
                        'type' => 'properties?project_name=' . $singleProject->title . '&project_detail=project-' . $singleProject->id,
                    ]);
                }

                return $this->success('Home Suggestion', $suggestions, 200);
            }
            return $this->success('Home Suggestion', [], 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function propertyPageSearch(Request $request)
    {
        try {
            $keyword = $request->keyword;

            $developers = Developer::Where('name', 'like', "%$keyword%")->approved()->active()->get()->map(function ($developer) {
                return [
                    'id' => $developer->id,
                    'name' => $developer->name,
                    'type' => 'developer-' . $developer->id,
                ];
            })->toArray();

            $communities = Community::Where('name', 'like', "%$keyword%")->approved()->active()->get()->map(function ($community) {
                return [
                    'id' => $community->id,
                    'name' => $community->name . "(" . $community->emirates . ")",
                    'type' => 'community-' . $community->id,
                ];
            })->toArray();
            $projects = Project::Where('title', 'like', "%$keyword%")->approved()->mainProject()->active()->get()->map(function ($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->title . "(" . $project->mainCommunity->name . ", " . $project->mainCommunity->emirates . ")",
                    'type' => 'project-' . $project->id,
                ];
            })->toArray();

            $suggestions = array();

            $suggestions =  array_merge($suggestions, $developers, $communities, $projects);

            return $this->success('Property Search Suggestion', $suggestions, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }

    public function projectPageSearch(Request $request)
    {
        try {
            $keyword = $request->keyword;

            $developers = Developer::Where('name', 'like', "%$keyword%")->approved()->active()->get()->map(function ($developer) {
                return [
                    'id' => $developer->id,
                    'name' => $developer->name,
                    'type' => 'developer-' . $developer->id,
                ];
            })->toArray();

            $communities = Community::Where('name', 'like', "%$keyword%")->approved()->active()->get()->map(function ($community) {
                return [
                    'id' => $community->id,
                    'name' => $community->name . "(" . $community->emirates . ")",
                    'type' => 'community-' . $community->id,
                ];
            })->toArray();
            $projects = Project::Where('title', 'like', "%$keyword%")->approved()->mainProject()->active()->get()->map(function ($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->title . "(" . $project->mainCommunity->name . ", " . $project->mainCommunity->emirates . ")",
                    'type' => 'project-' . $project->id,
                ];
            })->toArray();

            $suggestions = array();

            $suggestions =  array_merge($suggestions, $developers, $communities, $projects);

            return $this->success('Project Search Suggestion', $suggestions, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function get_google_reviews()
    {

        // URL to fetch
        $google_api = 'https://maps.googleapis.com/maps/api/place/details/json?placeid=' . env('PLACE_ID') . '&reviewsLimit=20&sensor=true&key=' . env('GOOGLE_KEY');

        // Fetch reviews with cURL
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $google_api);

        $response = curl_exec($ch);
        curl_close($ch);

        // JSON decode the text to associative array
        return json_decode($response, 'assoc');
    }
    public function getGoogleReivews()
    {
        try {
            $reviews = [];
            $reviews = $this->get_google_reviews();
            if ($reviews['status'] === 'OK') {
                $reviews = $reviews['result']['reviews'];
            }
            return $this->success('Google Review', $reviews, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage(), $exception->getCode());
        }
    }
    public function contactUsForm(Request $request)
    {
        try {

            $link = null;

            $token = '3MPHJP0BC63435345341';

            if ($request->formName == 'EmailerForm') {
                $messages = [
                    'email' => 'Email field is Required',
                ];
                $validator = Validator::make($request->all(), [
                    'email' => 'required',
                ], $messages);
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 401);
                }
            } else {
                $messages = [
                    'name' => 'Name field is Required ',
                    'phone' => 'Phone field is Required ',
                    'email' => 'Email field is Required',
                ];
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'phone' => 'required',
                    'email' => 'required',
                ], $messages);
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 401);
                }
            }

            if (in_array(
                $request->formName,
                [
                    "FooterContactForm",
                    "CallBackRequestForm",
                    "ResidentialSales&Leasing",
                    "CommercialSales&Leasing",
                    "Property/PortfolioManagement",
                    "HolidayHomes",
                    "MortgageServices",
                    "InvestmentConsultancy",
                    "GoldenVisaForm",
                    "sellContactForm",
                    "mortgageForm",
                    "bookACall",
                    "SpendAdayWithRange",
                    "alMarjan",
                    "ACallWithRange"
                ]
            )) {
                Log::info("formName:" . $request->formName);
                $data = [
                    'email' => $request->email,
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'message' => "Message-" . $request->message,
                    'agentEmail' =>  'ian@xpertise.ae',

                ];
                if ($request->formName == "mortgageForm") {

                    $request->merge([
                        'customer_name' => $request->name,
                        'customer_email' => $request->email,
                        'customer_phone' => $request->nationalNumber,
                        'customer_phone_country_code' => str_replace("+", "", $request->countryCode)
                    ]);

                    //LeadMovetoMortgageJob::dispatch($request->all());
                    Log::info($request->all());

                    $response = Http::withHeaders([
                        'Accept' => 'application/json',
                        'Auth-Token' => config('app.mortgage_token'),
                    ])->post(config('app.mortgage_api_url') . 'mortgage-lead-application-submit', $request->all());


                    if ($response->successful()) {
                        // Request was successful, handle the response
                        $responseData = $response->json(); // If expecting JSON response
                        Log::info('MortgageLeadJob DONE');
                        Log::info($responseData);
                        if ($response['data']) {
                            Log::info($response['data']['mortgage_lead_application_reference_number']);
                            $mortgageReferenNumber = $response['data']['mortgage_lead_application_reference_number'];

                            $request->merge([
                                'mortgage_lead_application_reference_number' => $mortgageReferenNumber,
                            ]);

                            // Exclude specific fields from the message
                            $excludedFields = ['CountryCode', 'NationalNumber', 'FullPhoneNumber', 'email', 'name', 'phone', 'agentEmail', 'formName', 'page', 'customer_name', 'customer_email', 'customer_phone', 'customer_phone_country_code'];
                            $messageDetails = collect($request->except($excludedFields))->filter(function ($value, $key) {
                                return !empty($value);
                            })->map(function ($value, $key) {
                                return ucfirst($key) . ": " . $value;
                            })->implode(", ");

                            // Add additional details to the message
                            $data['message'] = $messageDetails;
                            //$data = $this->CRMCampaignManagement($data, 263, 470, 2537);
                            $data = $this->CRMCampaignManagement($data, 270, 494, 2586);
                            CRMLeadJob::dispatch($data);
                        }
                        // Process the response data here
                    } else {
                        // Request failed, handle the error
                        $errorCode = $response->status();
                        $errorMessage = $response->body(); // Get the error message
                        // Handle the error here

                        Log::info('MortgageLeadJob ERROR DONE');
                        Log::info($errorMessage);
                    }
                }
                if ($request->formName == "alMarjan") {
                    $data = $this->CRMCampaignManagement($data, 218, 580, 2679);
                    CRMLeadJob::dispatch($data);
                }
                if($request->formName == "ACallWithRange"){
                    $data = $this->CRMCampaignManagement($data, 322, 584, 2681);
                    CRMLeadJob::dispatch($data);
                }
                if ($request->formName == "SpendAdayWithRange") {
                    $data = $this->CRMCampaignManagement($data, 270, 578, 2676);
                    CRMLeadJob::dispatch($data);
                }
                if ($request->formName == "FooterContactForm") {
                    $data = $this->CRMCampaignManagement($data, 270, 490, 2580);
                    CRMLeadJob::dispatch($data);
                }
                if ($request->formName == "bookACall") {
                    $data['message'] = "Page Url: " . $request->page . ", Date:" . $request->date . " , Time:" . $request->time . " , Message:" . $request->message;

                    $data = $this->CRMCampaignManagement($data, 270, 490, 2581);
                    CRMLeadJob::dispatch($data);
                }
                if ($request->formName == "CallBackRequestForm") {
                    //$data = $this->CRMCampaignManagement($data, 254, 458, 2514);
                    $data = $this->CRMCampaignManagement($data, 270, 491, 2583);
                    CRMLeadJob::dispatch($data);


                    // $response = Http::withHeaders([
                    //     'authorization-token' => config('crm_token'),
                    // ])->post(config('app.crm_url'), $data);


                    // if ($response->successful()) {
                    //     // Request was successful, handle the response
                    //     $responseData = $response->json(); // If expecting JSON response
                    //     Log::info('CRM DONE');
                    //     Log::info($responseData);
                    //     // Process the response data here
                    // } else {
                    //     // Request failed, handle the error
                    //     $errorCode = $response->status();
                    //     $errorMessage = $response->body(); // Get the error message
                    //     // Handle the error here

                    //     Log::info('CRM ERROR DONE');
                    //     Log::info($errorMessage);
                    // }
                }

                if ($request->formName == "ResidentialSales&Leasing") {
                    //$data = $this->CRMCampaignManagement($data, 256, 461, 2517);
                    $data = $this->CRMCampaignManagement($data, 270, 492, '', '', true, $request->formName, $request->formName);
                    CRMLeadJob::dispatch($data);
                }
                if ($request->formName == "CommercialSales&Leasing") {
                    $data = $this->CRMCampaignManagement($data, 270, 492, '', '', true, $request->formName, $request->formName);
                    //$data = $this->CRMCampaignManagement($data, 256, 461, 2518);
                    CRMLeadJob::dispatch($data);
                }
                if ($request->formName == "Property/PortfolioManagement") {
                    $data = $this->CRMCampaignManagement($data, 270, 492, '', '', true, $request->formName, $request->formName);
                    //$data = $this->CRMCampaignManagement($data, 256, 461, 2519);
                    CRMLeadJob::dispatch($data);
                }
                if ($request->formName == "HolidayHomes") {
                    $data = $this->CRMCampaignManagement($data, 270, 492, '', '', true, $request->formName, $request->formName);
                    //$data = $this->CRMCampaignManagement($data, 256, 461, 2520);
                    CRMLeadJob::dispatch($data);
                }
                if ($request->formName == "MortgageServices") {
                    $data = $this->CRMCampaignManagement($data, 270, 492, '', '', true, $request->formName, $request->formName);
                    //$data = $this->CRMCampaignManagement($data, 256, 461, 2521);
                    CRMLeadJob::dispatch($data);
                }
                if ($request->formName == "InvestmentConsultancy") {
                    $data = $this->CRMCampaignManagement($data, 270, 492, '', '', true, $request->formName, $request->formName);
                    //$data = $this->CRMCampaignManagement($data, 256, 461, 2522);
                    CRMLeadJob::dispatch($data);
                }
                if ($request->formName == "GoldenVisaForm") {
                    // $data = $this->CRMCampaignManagement($data, 257, 462, 2523);
                    $data = $this->CRMCampaignManagement($data, 270, 493, 2585);
                    CRMLeadJob::dispatch($data);
                }
                if ($request->formName == "sellContactForm") {
                    //$data = $this->CRMCampaignManagement($data, 258, 463, 2524);
                    $data = $this->CRMCampaignManagement($data, 270, 496, '', '', true, $request->formName, $request->formName);
                    CRMLeadJob::dispatch($data);
                }
            }
            $data = [
                'email' => $request->email,
                'name' => $request->name,
                'phone' => $request->phone,
                'message' => "Page Url:" . $request->page . " Message-" . $request->message,
            ];

            if ($request->has('superformName') && $request->superformName == "DubaiGuides") {
                Log::info("DubaiGuides");
                if ($request->has('sourceId')) {
                    //Log::info("sourceId-" . $request->sourceId);
                    //$data = $this->CRMCampaignManagement($data, 262, 468, $request->sourceId);
                    $data = $this->CRMCampaignManagement($data, 270, 495, '', '', true, $request->formName, $request->formName);
                    CRMLeadJob::dispatch($data);
                }
            }
            if ($request->formName == 'homePageBrochure') {
                $link = PageContent::WherePageName(config('constants.home.name'))->first();
                $link = $link->brochure;

                $data = $this->CRMCampaignManagement($data, 270, 490, 2582);
                CRMLeadJob::dispatch($data);
            } elseif ($request->formName == 'homePageCompanyProfile') {
                $link = PageContent::WherePageName(config('constants.home.name'))->first();
                $link = $link->ourProfile;

                $data = $this->CRMCampaignManagement($data, 270, 490, 2677);
                CRMLeadJob::dispatch($data);
            } elseif ($request->formName == 'GoldenVisaGuideForm') {

                $link = PageContent::WherePageName(config('constants.dubaiGuide.name'))->first();
                $link = $link->goldenVisa;
            } elseif ($request->formName == 'BuyerGuideForm') {
                $link = PageContent::WherePageName(config('constants.dubaiGuide.name'))->first();
                $link = $link->buyerGuide;
            } elseif ($request->formName == 'InvestmentGuideForm') {
                $link = PageContent::WherePageName(config('constants.dubaiGuide.name'))->first();
                $link = $link->propertiesGuide;
            } elseif ($request->formName == 'SellerGuideDownload') {

                //$data = $this->CRMCampaignManagement($data, 258, 463, 2527);
                $data = $this->CRMCampaignManagement($data, 270, 496, '', '', true, $request->formName, $request->formName);
                CRMLeadJob::dispatch($data);

                $link = PageContent::WherePageName(config('constants.sellerGuide.name'))->first();
                $link =  $link->sellerGuide;
            } elseif ($request->formName == 'projectBrochure') {
                $project = Project::where('slug', $request->project)->first();
               
                
                $currency = 'AED';
                $exchange_rate = 1;
                if(isset($request->currency)){
                    $currenyExist = Currency::where('name', $request->currency)->exists();
        
                    if($currenyExist){
                        $currency = $request->currency;
                        $exchange_rate = Currency::where('name', $request->currency)->first()->value;
                    }
                        
                }


                // Disable timestamps for this scope
                Project::withoutTimestamps(function () use ($project, $currency, $exchange_rate) {
                    $project->brochure_link = null;
                    $project->save();
                    
                   
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
                            'currency' => $currency,
                            'exchange_rate' => $exchange_rate,
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

                        
                });
                    
                $link = $project->brochure_link;


                 $data = $this->CRMCampaignManagement($data, 270, 497, '', '', true, $project->title, $project->reference_number);
                // CRMLeadJob::dispatch($data);
            } elseif ($request->formName == 'propertyBrochure' || $request->formName == 'propertySaleOfferDownloadForm') {
                $property = Property::where('slug', $request->property)->first();


                $currency = 'AED';
                $exchange_rate = 1;
                if(isset($request->currency)){
                    $currenyExist = Currency::where('name', $request->currency)->exists();
        
                    if($currenyExist){
                        $currency = $request->currency;
                        $exchange_rate = Currency::where('name', $request->currency)->first()->value;
                    }
                        
                }

                if ($request->formName == 'propertyBrochure') {

                    Property::withoutTimestamps(function () use ($property, $currency, $exchange_rate) {
                        $property->brochure_link = null;
                        $property->save();
                        
                       
                        view()->share(['property' => $property, 
                            'currency' => $currency,
                            'exchange_rate' => $exchange_rate
                        ]);
                        $pdf = PDF::loadView('pdf.propertyBrochure');
                        $pdfContent = $pdf->output();
    
                        $saleOffer = PDF::loadView('pdf.propertySaleOffer');
                        $saleOfferPdf = $saleOffer->output();
                        //return $saleOfferPdf->stream();
    
                        $property->clearMediaCollection('brochures');
                        // $property->clearMediaCollection('saleOffers');
    
    
                        $property->addMediaFromString($pdfContent)
                            ->usingFileName($property->name . '-brochure.pdf')
                            ->toMediaCollection('brochures', 'propertyFiles');
    
                        // $property->addMediaFromString($saleOfferPdf)
                        //     ->usingFileName($property->name . '-saleoffer.pdf')
                        //     ->toMediaCollection('saleOffers', 'propertyFiles');
    
                        $property->save();
                        $property->brochure_link = $property->brochure;
                        $property->updated_brochure = 1;
                        $property->save();
                            
                    });

                    $link = $property->brochure_link;

                }
                if($request->formName == 'propertySaleOfferDownloadForm')
                {
                    Property::withoutTimestamps(function () use ($property, $currency, $exchange_rate) {
                        $property->saleoffer_link = null;
                        $property->save();
                        
                       
                        view()->share(['property' => $property, 
                            'currency' => $currency,
                            'exchange_rate' => $exchange_rate
                        ]);

    
                        $saleOffer = PDF::loadView('pdf.propertySaleOffer');
                        $saleOfferPdf = $saleOffer->output();
                     
                        $property->clearMediaCollection('saleOffers');
    
                        $property->addMediaFromString($saleOfferPdf)
                            ->usingFileName($property->name . '-saleoffer.pdf')
                            ->toMediaCollection('saleOffers', 'propertyFiles');
    
                        $property->save();
                        $property->saleoffer_link = $property->saleOffer;
                        $property->save();
                            
                    });

                    $link = $property->saleoffer_link;
                }

                $data['message'] = "Property URL-" . $property->slug;
                //$data = $this->CRMCampaignManagement($data, 267, 481, "", '', true, $property->name);
                $email = $property->agent ? $property->agent->email : '';

                if ($email == 'lester@range.ae') {
                    $email = "";
                }
                $data = $this->CRMCampaignManagement($data, 270, 498, '', $email, true, $property->name, $property->reference_number, $request->formName);

                Log::info($data);
            }
            $lead = new Lead;
            $lead->email = $request->email;
            $lead->name = $request->name;
            $lead->phone = $request->phone;
            $lead->page_url = $request->page;
            $lead->message     = $request->message;
            $lead->booking_time = $request->time;
            $lead->booking_date = $request->date;
            $lead->form_name = $request->formName;
            $lead->save();

            return $this->success('Form Submit',['verify' => true, 'link' => $link], 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function CRMCampaignManagement($data, $campaignId, $sourceId, $subSourceId, $agentEmail = '', $needToCreateSubSource = false, $subSourceName = '', $subSourceReference = '', $requestFormName = '')
    {
        $data["campaignId"] = $campaignId;
        $data["sourceId"] = $sourceId;
        $data["subSourceId"] = $subSourceId;
        $data['needToCreateSubSource'] = $needToCreateSubSource;
        $data['subSourceName'] = $subSourceName;
        $data['reference'] = $subSourceReference;
        $data['requestFormName'] = $requestFormName;
        // Check if $agentEmail is empty, assign default value if so
        if (empty($agentEmail)) {
            $agentEmail = 'ian@xpertise.ae';
        }

        // Add agentEmail to the data array
        $data["agentEmail"] = $agentEmail;

        return $data;
    }

    public function sendOtp(Request $request)
    {
        DB::beginTransaction();
        try {
            $messages = [
                'name' => 'Name field is Required ',
                'phone' => 'Phone field is Required ',
                'email' => 'Email field is Required',
            ];
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required',
                'email' => 'required',
            ], $messages);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }


            $otp = rand(100000, 999999); // Generate OTP
            $expired_at = now()->addMinutes(2)->format('Y-m-d H:i:s'); // Set expiration time

            // Save OTP in database
            $otpModel = new OtpVerification;
            $otpModel->phone = $request->phone;
            $otpModel->otp = $otp;
            $otpModel->expired_at = $expired_at;
            $otpModel->form_name = $request->formName;
            $otpModel->save();

            // $lead = new Lead;
            // $lead->email = $request->email;
            // $lead->name = $request->name;
            // $lead->phone = $request->phone;
            // $lead->page_url = $request->page;
            // $lead->message	 = $request->message;
            // $lead->booking_time = $request->time;
            // $lead->booking_date = $request->date;
            // $lead->form_name = $request->formName;
            // $lead->otpId = $otpModel->id;
            // $lead->save();
            //dd($request->all());
            $message = "Welcome to Range! Your OTP is " . $otp . ". Explore premium opportunities & unlock your investment potential with us. *This OTP is valid for 1-minute";
            // "Welcome to Range! Your unique OTP for accessing our exclusive offering is ".$otp.". Explore premium opportunities and unlock your investment potential with us.*This OTP is valid for 1-minute";

            $response = Http::get('https://www.mshastra.com/sendurl.aspx', [
                'user' => 'rangeint',
                'pwd' => 'Range@23',
                'senderid' => 'Rangelnv',
                'CountryCode' => $request->countryCode,
                'mobileno' => $request->nationalNumber,
                'msgtext' =>  $message,
                'smstype' => '0'
            ]);

            DB::commit();

            if ($response->successful()) {
                $data = $response->json();
                Log::info($data);
                return $this->success('Form Submit', [], 200);
            } else {
                Log::error('SMS API Error: ' . $response->body());
                return $this->success('Form Submit ERROR', [], 200);
            }

            // return $this->success('Form Submit', [], 200);

        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function verifyOtp(Request $request)
    {
        $data = [
            'email' => $request->email,
            'name' => $request->name,
            'phone' => $request->phone,
            'message' => "Page Url:" . $request->page . " Message-" . $request->message,
        ];

        try {
            $link = null;
            $messages = [
                'fullPhoneNumber' => 'Phone field is Required ',
                'otp' => 'OTP field is Required',
            ];
            $validator = Validator::make($request->all(), [
                'fullPhoneNumber' => 'required',
                'otp' => 'required',
            ], $messages);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $otpModel =  OtpVerification::where('phone', str_replace(' ', '', $request->fullPhoneNumber))
                ->where('otp', $request->otp)
                ->where('is_used', 0)
                ->where('expired_at', '>', now()->format('Y-m-d H:i:s'))
                ->first();

            if ($otpModel) {
                $otpModel->is_used = 1;
                $otpModel->save();
                if ($request->has('superformName') && $request->superformName == "DubaiGuides") {
                    Log::info("DubaiGuides");
                    if ($request->has('sourceId')) {
                        //Log::info("sourceId-" . $request->sourceId);
                        //$data = $this->CRMCampaignManagement($data, 262, 468, $request->sourceId);
                        $data = $this->CRMCampaignManagement($data, 270, 495, '', '', true, $request->formName, $request->formName);
                        CRMLeadJob::dispatch($data);
                    }
                }
                if ($request->formName == 'homePageBrochure') {
                    $link = PageContent::WherePageName(config('constants.home.name'))->first();
                    $link = $link->brochure;

                    $data = $this->CRMCampaignManagement($data, 270, 490, 2582);
                    CRMLeadJob::dispatch($data);
                } elseif ($request->formName == 'homePageCompanyProfile') {
                    $link = PageContent::WherePageName(config('constants.home.name'))->first();
                    $link = $link->ourProfile;

                    $data = $this->CRMCampaignManagement($data, 270, 490, 2677);
                    CRMLeadJob::dispatch($data);
                } elseif ($request->formName == 'GoldenVisaGuideForm') {

                    $link = PageContent::WherePageName(config('constants.dubaiGuide.name'))->first();
                    $link = $link->goldenVisa;
                } elseif ($request->formName == 'BuyerGuideForm') {
                    $link = PageContent::WherePageName(config('constants.dubaiGuide.name'))->first();
                    $link = $link->buyerGuide;
                } elseif ($request->formName == 'InvestmentGuideForm') {
                    $link = PageContent::WherePageName(config('constants.dubaiGuide.name'))->first();
                    $link = $link->propertiesGuide;
                } elseif ($request->formName == 'SellerGuideDownload') {

                    //$data = $this->CRMCampaignManagement($data, 258, 463, 2527);
                    $data = $this->CRMCampaignManagement($data, 270, 496, '', '', true, $request->formName, $request->formName);
                    CRMLeadJob::dispatch($data);

                    $link = PageContent::WherePageName(config('constants.sellerGuide.name'))->first();
                    $link =  $link->sellerGuide;
                } elseif ($request->formName == 'projectBrochure') {
                    $project = Project::where('slug', $request->project)->first();
                   
                    
                    $currency = 'AED';
                    $exchange_rate = 1;
                    if(isset($request->currency)){
                        $currenyExist = Currency::where('name', $request->currency)->exists();
            
                        if($currenyExist){
                            $currency = $request->currency;
                            $exchange_rate = Currency::where('name', $request->currency)->first()->value;
                        }
                            
                    }


                    // Disable timestamps for this scope
                    Project::withoutTimestamps(function () use ($project, $currency, $exchange_rate) {
                        $project->brochure_link = null;
                        $project->save();
                        
                       
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
                                'currency' => $currency,
                                'exchange_rate' => $exchange_rate,
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

                            
                    });
                        
                    $link = $project->brochure_link;


                     $data = $this->CRMCampaignManagement($data, 270, 497, '', '', true, $project->title, $project->reference_number);
                    // CRMLeadJob::dispatch($data);
                } elseif ($request->formName == 'propertyBrochure' || $request->formName == 'propertySaleOfferDownloadForm') {
                    $property = Property::where('slug', $request->property)->first();


                    $currency = 'AED';
                    $exchange_rate = 1;
                    if(isset($request->currency)){
                        $currenyExist = Currency::where('name', $request->currency)->exists();
            
                        if($currenyExist){
                            $currency = $request->currency;
                            $exchange_rate = Currency::where('name', $request->currency)->first()->value;
                        }
                            
                    }

                    if ($request->formName == 'propertyBrochure') {

                        Property::withoutTimestamps(function () use ($property, $currency, $exchange_rate) {
                            $property->brochure_link = null;
                            $property->save();
                            
                           
                            view()->share(['property' => $property, 
                                'currency' => $currency,
                                'exchange_rate' => $exchange_rate
                            ]);
                            $pdf = PDF::loadView('pdf.propertyBrochure');
                            $pdfContent = $pdf->output();
        
                            $saleOffer = PDF::loadView('pdf.propertySaleOffer');
                            $saleOfferPdf = $saleOffer->output();
                            //return $saleOfferPdf->stream();
        
                            $property->clearMediaCollection('brochures');
                            // $property->clearMediaCollection('saleOffers');
        
        
                            $property->addMediaFromString($pdfContent)
                                ->usingFileName($property->name . '-brochure.pdf')
                                ->toMediaCollection('brochures', 'propertyFiles');
        
                            // $property->addMediaFromString($saleOfferPdf)
                            //     ->usingFileName($property->name . '-saleoffer.pdf')
                            //     ->toMediaCollection('saleOffers', 'propertyFiles');
        
                            $property->save();
                            $property->brochure_link = $property->brochure;
                            $property->updated_brochure = 1;
                            $property->save();
                                
                        });

                        $link = $property->brochure_link;

                    }
                    if($request->formName == 'propertySaleOfferDownloadForm')
                    {
                        Property::withoutTimestamps(function () use ($property, $currency, $exchange_rate) {
                            $property->saleoffer_link = null;
                            $property->save();
                            
                           
                            view()->share(['property' => $property, 
                                'currency' => $currency,
                                'exchange_rate' => $exchange_rate
                            ]);
    
        
                            $saleOffer = PDF::loadView('pdf.propertySaleOffer');
                            $saleOfferPdf = $saleOffer->output();
                         
                            $property->clearMediaCollection('saleOffers');
        
                            $property->addMediaFromString($saleOfferPdf)
                                ->usingFileName($property->name . '-saleoffer.pdf')
                                ->toMediaCollection('saleOffers', 'propertyFiles');
        
                            $property->save();
                            $property->saleoffer_link = $property->saleOffer;
                            $property->save();
                                
                        });

                        $link = $property->saleoffer_link;
                    }
                    

                    $data['message'] = "Property URL-" . $property->slug;
                    //$data = $this->CRMCampaignManagement($data, 267, 481, "", '', true, $property->name);
                    $email = $property->agent ? $property->agent->email : '';

                    if ($email == 'lester@range.ae') {
                        $email = "";
                    }
                    $data = $this->CRMCampaignManagement($data, 270, 498, '', $email, true, $property->name, $property->reference_number, $request->formName);
                    Log::info($data);

                    if($property->property_source == 'xml'){


                        $fullPhoneNumber = $request->fullPhoneNumber; // e.g., ' +971 58 6238699 '

                        // Remove leading/trailing spaces
                        $fullPhoneNumber = trim($fullPhoneNumber);

                        // Remove all spaces within the phone number
                        $fullPhoneNumber = preg_replace('/\s+/', '', $fullPhoneNumber);

                        // Now you can proceed with separating the country code, area code, and phone number
                        $pattern = '/^(\+?\d{1,3})(\d{2})(\d{6,})$/';

                        $countryCode = null;   // e.g., +971
                        $areaCode = null;      // e.g., 58
                        $phoneNumber = null;   // e.g., 6238699

                        if (preg_match($pattern, $fullPhoneNumber, $matches)) {
                            $countryCode = $matches[1];   // e.g., +971
                            $areaCode = $matches[2];      // e.g., 58
                            $phoneNumber = $matches[3];   // e.g., 6238699
                        } else {
                            // Handle error case where number doesn't match expected format
                            Log::info('Invalid phone number format');
                        }

                        $propertySlug = "https://www.range.ae/properties/".$property->slug;
                        
                        $Remarks = "Hi, I am interested in your property on website: $propertySlug";
    
                        // $response = Http::get('https://webapi.goyzer.com/Company.asmx/ContactInsert2', [
                        //     'AccessCode' => '$R@nGe!NteRn@t!on@l',
                        //     'GroupCode' => '5084',
                        //     'TitleID' => '79743',
                        //     'FirstName' => $request->name,
                        //     'FamilyName' => '',
                        //     'MobileCountryCode' => $countryCode,
                        //     'MobileAreaCode' => $areaCode,
                        //     'MobilePhone' => $phoneNumber,
                        //     'TelephoneCountryCode' => '',
                        //     'TelephoneAreaCode' => '',
                        //     'Telephone' => '',
                        //     'Email' => $request->email,
                        //     'NationalityID' => '',
                        //     'CompanyID' => '',
                        //     'Remarks' => $Remarks,
                        //     'RequirementType' => '91212',
                        //     'ContactType' => '1',
                        //     'CountryID' => $property->CountryID,
                        //     'StateID' =>  $property->StateID,
                        //     'CityID' =>  $property->CityID,
                        //     'DistrictID' => $property->DistrictID,
                        //     'CommunityID' => $property->CommunityID,
                        //     'SubCommunityID' => $property->SubCommunityID,
                        //     'PropertyID' => $property->PropertyID,
                        //     'UnitID' => $property->UnitID,
                        //     'UnitType' => $property->UnitType,
                        //     'MethodOfContact' => '196061',
                        //     'MediaType' => '79266',
                        //     'MediaName' => '78340',
                        //     'ReferredByID' => '1000',
                        //     //'ReferredToID' =>  $property->ReferredToID,
                        //     'ReferredToID' =>  1219,
                        //     'DeactivateNotification' => '0.0.0.0',
                        //     'Bedroom' => $property->bedrooms,
                        //     'Budget' => '',
                        //     'Budget2' => '',
                        //     'RequirementCountryID' => '',
                        //     'ExistingClient' => '',
                        //     'CompaignSource' => '',
                        //     'CompaignMedium' => '',
                        //     'Company' => '',
                        //     'NumberOfEmployee' => '',
                        //     'LeadStageId' => '2',
                        //     'ActivityDate' => '',
                        //     'ActivityTime' => '',
                        //     'ActivityTypeId' => '',
                        //     'ActivitySubject' => '',
                        //     'ActivityRemarks' => '',
                        // ]);
                        
                        // Log::info("goyzer-lead");
                        // Log::info($response);
                        // if ($response->successful()) {
                            
                        //     Log::info("success");
                        //     Log::info($response->body());
                            
                        // } else {
                        //     Log::info("error");
                        //     Log::info('response->status-'.$response->status());
                        // }


                        $responseUrl = "https://webapi.goyzer.com/Company.asmx/ContactInsert2?AccessCode=$R@nGe!NteRn@t!on@l&GroupCode=5084&
                                TitleID=79743&FirstName=$request->name&FamilyName=&MobileCountryCode=$countryCode&MobileAreaCode=$areaCode&MobilePhone=$phoneNumber&TelephoneCountryCode=&
                                TelephoneAreaCode=&Telephone=&Email=$request->nam&NationalityID=&CompanyID=&
                                Remarks=$Remarks&RequirementType=91212&
                                ContactType=1&CountryID=$property->CountryID&StateID=$property->StateID&CityID=$property->CityID&DistrictID=$property->DistrictID&
                                CommunityID=$property->CommunityID&SubCommunityID=$property->SubCommunityID&PropertyID=$property->PropertyID&UnitID=$property->UnitID&
                                UnitType=$property->UnitType&MethodOfContact=196061&MediaType=79266&MediaName=78340&ReferredByID=1000&ReferredToID=1219&DeactivateNotification=0.0.0.0&
                                Bedroom=2&Budget=&Budget2=&RequirementCountryID=&ExistingClient=&CompaignSource=&CompaignMedium=&Company=&NumberOfEmployee=&LeadStageId=2&ActivityDate=&ActivityTime=&ActivityTypeId=&ActivitySubject=&ActivityRemarks=";

                        // Log the constructed URL
                        Log::info("goyzer-lead");
                        Log::info($responseUrl);

                        // Send the HTTP request
                        $response = Http::get($responseUrl);

                        if ($response->successful()) {
                            Log::info("Success");
                            Log::info($response->body());
                        } else {
                            Log::info("Error");
                            Log::info('Response status: ' . $response->status());
                        }

                    }
                   // CRMLeadJob::dispatch($data);
                }
                return $this->success('Form Submit', ['verify' => true, 'link' => $link], 200);
            } else {
                return $this->success('Form Submit', ['verify' => false], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }

    function CRMCampaignManagementCRM($data)
    {
        Log::info('CRMLeadJob Start');
        try {

            $response = Http::withHeaders([
                'authorization-token' => '3MPHJP0BC63435345341',
            ])->post(config('app.crm_url'), $data);


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
