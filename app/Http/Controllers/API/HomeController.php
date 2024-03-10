<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{
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
    DubaiGuideResource,
    SellGuideResource,
    DeveloperListResource
};
use App\Jobs\{
    CRMLeadJob
};
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    public function sendSMS()
    {
        try {

            // $url ="https://mshastra.com/sendurl.aspx?user=rangeint&pwd=Range@23&senderid=rangeint&mobileno=586238697&msgtext=Hello&CountryCode=+971";
            // $ch = curl_init($url);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // $curl_scraped_page = curl_exec($ch);
            // curl_close($ch);
            // dd($curl_scraped_page);
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
            //dd( $response);
            if ($response->successful()) {
                $data = $response->json();
                return $this->success('SMS Data', $response, 200);
            } else {
                // Handle the error
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function bankNames()
    {
        try {
            $bankNames = [
                ["value" => "Dubai Islamic Bank", "label" => "Dubai Islamic Bank"],
                ["value" => "Citi Bank", "label" => "Citi Bank"],
                ["value" => "Emirate Islamic Bank", "label" => "Emirate Islamic Bank"],
                [
                    "value" => "ADCB Abu Dhabi Commercial Bank",
                    "label" => "ADCB Abu Dhabi Commercial Bank",
                ],
                ["value" => "Abu Dhabi Islamic Bank", "label" => "Abu Dhabi Islamic Bank"],
                ["value" => "Ajman Bank", "label" => "Ajman Bank"],
                ["value" => "Arab Bank", "label" => "Arab Bank"],
                ["value" => "Bank Of Baroda", "label" => "Bank Of Baroda"],
                [
                    "value" => "Commercial Bank International",
                    "label" => "Commercial Bank International",
                ],
                [
                    "value" => "CBD Commercial Bank Of Dubai",
                    "label" => "CBD Commercial Bank Of Dubai",
                ],
                ["value" => "Emirates NBD", "label" => "Emirates NBD"],
                ["value" => "FAB First Abu Dhabi Bank", "label" => "FAB First Abu Dhabi Bank"],
                ["value" => "HSBC", "label" => "HSBC"],
                ["value" => "Mashreq Bank", "label" => "Mashreq Bank"],
                ["value" => "National Bank Of Fujairah", "label" => "National Bank Of Fujairah"],
                ["value" => "RAK Bank", "label" => "RAK Bank"],
                ["value" => "Sharjah Islamic Bank", "label" => "Sharjah Islamic Bank"],
                ["value" => "Standard Chartered Bank", "label" => "Standard Chartered Bank"],
                ["value" => "United Arab Bank", "label" => "United Arab Bank"],
            ];

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
    public function homeData()
    {
        try {
            // $communities = HomeCommunitiesResource::collection(Community::active()->approved()->home()->limit(12)->orderByRaw('ISNULL(communityOrder)')->orderBy('communityOrder', 'asc')->get() );
            $communities = HomeCommunitiesResource::collection(Community::active()->approved()->orderByRaw('ISNULL(communityOrder)')->orderBy('communityOrder', 'asc')->get());
            $testimonials =  HomeTestimonial::collection(Testimonial::select()->active()->latest()->get());

            $allProjects = Project::with(['accommodation', 'subProjects', 'completionStatus'])->mainProject()->approved()->active()->home();

            $displayProjects = clone $allProjects;

            $displayProjects = $displayProjects->orderByRaw('ISNULL(projectOrder)')->orderBy('projectOrder', 'asc')->take(8);


            $projects = HomeProjectResource::collection($displayProjects->get());
            $newProjects = ProjectOptionResource::collection($allProjects->OrderBy('title', 'asc')->get());
            $mapProjects = HomeMapProjectsResource::collection($allProjects->orderByRaw('ISNULL(projectOrder)')->orderBy('projectOrder', 'asc')->get());
            $brochure = PageContent::WherePageName(config('constants.home.name'))->first();
            $developers = DeveloperListResource::collection(Developer::active()->approved()->home()->orderByRaw('ISNULL(developerOrder)')->orderBy('developerOrder', 'asc')->get());

            // Function to convert number to words
            function convertToWords($number)
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
                    $croreStr = convertToWords($crore) . ' crore ';
                    return $croreStr;
                }

                // If the number is greater than or equal to 1 million, process million part
                if ($number >= 1000000) {
                    $million = floor($number / 1000000);
                    $remaining = $number % 1000000;
                    $millionStr = convertToWords($million) . ' million ';
                    return $millionStr;
                }

                // If the number is greater than or equal to 1 lakh, process lakh part
                if ($number >= 100000) {
                    $lakh = floor($number / 100000);
                    $remaining = $number % 100000;
                    $lakhStr = convertToWords($lakh) . ' lakh ';
                    return $lakhStr;
                }

                // If the number is greater than or equal to 1 thousand, process thousand part
                if ($number >= 1000) {
                    $thousand = floor($number / 1000);
                    $remaining = $number % 1000;
                    $thousandStr = convertToWords($thousand) . ' thousand ';
                    return $thousandStr;
                }

                // If the number is greater than 100, process hundred part
                if ($number >= 100) {
                    $hundred = floor($number / 100);
                    $remaining = $number % 100;
                    $hundredStr = convertToWords($hundred) . ' hundred ';
                    return $hundredStr;
                }

                // If the number is greater than 20 and not a multiple of 10, process tens and ones parts
                $ten = floor($number / 10);
                $one = $number % 10;
                return $tens[$ten] . ' ' . $ones[$one];
            }


            // Function to convert words to number
            function convertToNumber($word)
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

            $text = [];
            foreach ($combinedArray as $row) {
                $text[] =  $this->convertToNumber($row);
            }
            sort($text);

            $data = [
                'formattedNumbers' => $text,
                'communities' => $communities,
                'projects' => $projects,
                'newProjects' => $newProjects,
                'testimonials' => $testimonials,
                'mapProjects' => $mapProjects,
                'developers' => $developers,
                'brochure' => $brochure->brochure,


            ];

            return $this->success('Home Data', $data, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function homeMeta()
    {
        try {
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
                'description' => $description,
                'keywords' => $keywords,
            ];

            return $this->success('Home Data Meta', $data, 200);
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
                    "sellContactForm"
                ]
            )) {
                Log::info("formName" . $request->formName);
                $data = [
                    'email' => $request->email,
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'message' => "Page Url:" . $request->page . " Message-" . $request->message,
                    'agentEmail' =>  'ian@xpertise.ae',

                ];

                if ($request->formName == "FooterContactForm") {
                    $data = $this->CRMCampaignManagement($data, 1, 1, 1);
                }

                if ($request->formName == "CallBackRequestForm") {
                    $data = $this->CRMCampaignManagement($data, 254, 458, 2514);
                    CRMLeadJob::dispatch($data);
                }

                if ($request->formName == "ResidentialSales&Leasing") {
                    $data = $this->CRMCampaignManagement($data, 256, 461, 2517);
                    CRMLeadJob::dispatch($data);
                }
                if ($request->formName == "CommercialSales&Leasing") {
                    $data = $this->CRMCampaignManagement($data, 256, 461, 2518);
                    CRMLeadJob::dispatch($data);
                }
                if ($request->formName == "Property/PortfolioManagement") {
                    $data = $this->CRMCampaignManagement($data, 256, 461, 2519);
                    CRMLeadJob::dispatch($data);
                }
                if ($request->formName == "HolidayHomes") {
                    $data = $this->CRMCampaignManagement($data, 256, 461, 2520);
                    CRMLeadJob::dispatch($data);
                }
                if ($request->formName == "MortgageServices") {
                    $data = $this->CRMCampaignManagement($data, 256, 461, 2521);
                    CRMLeadJob::dispatch($data);
                }
                if ($request->formName == "InvestmentConsultancy") {
                    $data = $this->CRMCampaignManagement($data, 256, 461, 2522);
                    CRMLeadJob::dispatch($data);
                }
                if ($request->formName == "GoldenVisaForm") {
                    $data = $this->CRMCampaignManagement($data, 257, 462, 2523);
                    CRMLeadJob::dispatch($data);
                }
                if ($request->formName == "sellContactForm") {
                    $data = $this->CRMCampaignManagement($data, 258, 463, 2524);
                    CRMLeadJob::dispatch($data);
                }
            }




            // Log::info("Form-" . $request->formName . "Data-");
            // Log::info($data);

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

            return $this->success('Form Submit', [], 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function CRMCampaignManagement($data, $campaignId, $sourceId, $subSourceId, $agentEmail = '')
    {
        $data["campaignId"] = $campaignId;
        $data["sourceId"] = $sourceId;
        $data["subSourceId"] = $subSourceId;

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
                return $this->success('Form Submit', [], 200);
            } else {
                \Log::error('SMS API Error: ' . $response->body());
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
                        Log::info("sourceId-" . $request->sourceId);
                        $data = $this->CRMCampaignManagement($data, 262, 468, $request->sourceId);
                        CRMLeadJob::dispatch($data);
                    }
                }
                if ($request->formName == 'homePageBrochure') {
                    $link = PageContent::WherePageName(config('constants.home.name'))->first();
                    $link = $link->brochure;
                } elseif ($request->formName == 'GoldenVisaGuideForm') {

                    $link = PageContent::WherePageName(config('constants.dubaiGuide.name'))->first();
                    $link = $link->goldenVisa;
                } elseif ($request->formName == 'BuyerGuideForm') {
                    $link = PageContent::WherePageName(config('constants.dubaiGuide.name'))->first();
                    $link = $link->buyerGuide;
                } elseif ($request->formName == 'InvestmentGuideForm') {
                    $link = PageContent::WherePageName(config('constants.dubaiGuide.name'))->first();
                    $link = $link->propertiesGuide;
                } elseif ($request->formName == 'SellerGuideDownloadForm') {

                    $data = $this->CRMCampaignManagement($data, 258, 463, 2527);
                    CRMLeadJob::dispatch($data);

                    $link = PageContent::WherePageName(config('constants.sellerGuide.name'))->first();
                    $link =  $link->sellerGuide;
                } elseif ($request->formName == 'projectBrochure') {
                    $project = Project::where('slug', $request->project)->first();
                    $link = $project->brochure;
                } elseif ($request->formName == 'propertyBrochure') {
                    $property = Property::where('slug', $request->property)->first();
                    $link = $property->brochure;
                }
                return $this->success('Form Submit', ['verify' => true, 'link' => $link], 200);
            } else {
                return $this->success('Form Submit', ['verify' => false], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
}
