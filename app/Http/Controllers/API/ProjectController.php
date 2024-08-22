<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Project,
    WebsiteSetting,
    Accommodation,
    Category,
    Community,
    Property,
    CompletionStatus,
    Currency
};
use DB;
use App\Http\Resources\{
    SingleProjectResource,
    NearByProjectResource,
    NearByProjectsResource,
    ProjectListResource,
    AmenitiesNameResource,
    AccommodationListResource,
    ProjectCollection
};
use Illuminate\Support\Arr;

class ProjectController extends Controller
{

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

    public function areaList()
    {
        try {

            // Fetch results from the database
            $results = DB::select("
                SELECT area
                FROM projects
                WHERE deleted_at IS NULL
                AND status = 'active'
                AND is_approved = 'approved'
                AND area IS NOT NULL
                AND starting_price REGEXP '^[0-9]+$'
                GROUP BY area
                ORDER BY area;
            ");

            // Initialize arrays to store starting prices in words
            $thousands = [];
            $lakhs = [];
            $crores = [];
            $millions = [];
            $billions = [];

            // Convert each starting price to words and categorize them
            foreach ($results as $row) {
                $startingPrice = (int)$row->area;
                if ($startingPrice >= 1000000000) {
                    $billions[] = $this->convertToWords($startingPrice);
                } elseif ($startingPrice >= 10000000) {
                    $crores[] = $this->convertToWords($startingPrice);
                } elseif ($startingPrice >= 100000) {
                    $lakhs[] = $this->convertToWords($startingPrice);
                } elseif ($startingPrice >= 1000) {
                    $thousands[] = $this->convertToWords($startingPrice);
                } elseif ($startingPrice >= 100) {
                    $hundreds[] = $this->convertToWords($startingPrice);
                } elseif ($startingPrice >= 1000000) {
                    $millions[] = $this->convertToWords($startingPrice);
                }
            }

            $combinedArray = array_merge(array_unique($hundreds), array_unique($thousands), array_unique($lakhs), array_unique($crores), array_unique($millions), array_unique($billions));

            $text = [];
            foreach ($combinedArray as $row) {
                $text[] =  $this->convertToNumber($row);
            }
            sort($text);

            return $this->success('Project Area List', ['formattedNumbers' => $text], 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function priceList()
    {

        try {

            // Fetch results from the database
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

            return $this->success('Project Price List', ['formattedNumbers' => $text], 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function minPriceNumber($minPrice)
    {
        // Convert the number to a string and get its length
        $minPriceLength = strlen((string) $minPrice);
        // Subtract 1 from the length
        $adjustedLength = $minPriceLength - 1;
        // Generate a string of zeros with the adjusted length
        $zeros = str_repeat("0", $adjustedLength);
        // Convert the number to a string
        $numberAsString = (string) $minPrice;
        // Extract the first character
        $firstDigit = $numberAsString[0];

        return (int) $firstDigit . $zeros;
    }



    public function oldpriceList()
    {
        try {
            $results = DB::select("
                        SELECT MIN(starting_price) AS min_price, MAX(starting_price) AS max_price
                        FROM projects
                        WHERE deleted_at IS NULL
                        AND status = 'active'
                        AND starting_price IS NOT NULL
                        AND starting_price REGEXP '^[0-9]+$'
                    ");
            // Convert minPrice and maxPrice to integers
            $minPrice = intval($results[0]->min_price);
            $maxPrice = intval($results[0]->max_price);


            $minPrice = $this->minPriceNumber($minPrice);
            $maxPrice = $this->minPriceNumber($maxPrice);


            $priceGap = $this->getGap($minPrice);

            // Initialize the price array with the minPrice
            $priceArray = [$minPrice];

            // Initialize the current price
            $currentPrice = $minPrice;

            // Loop to generate the price array list
            while ($currentPrice + $priceGap <= $maxPrice) {
                // Calculate the next price element by adding priceGap to the current price
                $nextPrice = $currentPrice + $priceGap;

                // Add the next price to the price array
                $priceArray[] = $nextPrice;

                // Update the current price
                $currentPrice = $nextPrice;
                // 2100000, // 7bdigii

                // Adjust the price gap based on the next price
                if ($nextPrice >= 100 && $nextPrice < 1000) {
                    $priceGap = 100;
                } elseif ($nextPrice >= 1000 && $nextPrice < 10000) { // price lies 1k- 10k  
                    $priceGap = 10000;
                } elseif ($nextPrice >= 10000 && $nextPrice < 100000) { // price lies 10k- 100k  60k  
                    $priceGap = 20000;
                } elseif ($nextPrice >= 100000 && $nextPrice < 1000000) { // price lies 100k- 1000000 1M
                    $priceGap = 200000;
                } elseif ($nextPrice >= 2500000 && $nextPrice < 25000000) { // price lies 1M- 10M 
                    $priceGap = 2000000;
                } elseif ($nextPrice >= 10000000 && $nextPrice < 100000000) {
                    $priceGap = 10000000;
                }
            }

            // Output the price array list
            $data = [
                'cout' => count($priceArray),
                'formattedNumbers' => $priceArray,
                'minPrice' => $results[0]->min_price,
                'maxPrice' => $results[0]->max_price,
            ];
            return $this->success('Project Price List', $data, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }

    public function getGap($minPrice)
    {
        // Convert the number to a string and get its length
        $minPriceLength = strlen((string) $minPrice);
        // Subtract 1 from the length
        $adjustedLength = $minPriceLength - 1;
        // Generate a string of zeros with the adjusted length
        $zeros = str_repeat("0", $adjustedLength);
        // Convert the number to a string
        $numberAsString = (string) $minPrice;

        return (int) 1 . $zeros;
    }

    public function projectOfferTypes()
    {
        try {
            $completionStatuses = CompletionStatus::active()->where('for_property', 0)->latest()->get()->map(function ($type) {
                return [
                    'value' => $type->id,
                    'label' => $type->name
                ];
            });

            $completionStatuses->prepend([
                'value' => '',
                'label' => 'All'
            ]);
            return $this->success('offerTypes', $completionStatuses, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function projectOptions()
    {
        try {
            $projectOptions = Project::active()->mainProject()->approved()->OrderBy('title', 'asc')->get()->map(function ($project) {
                return [
                    'value' => $project->id,
                    'label' => $project->title
                ];
            });
            $projectOptions->prepend([
                'value' => '',
                'label' => 'All'
            ]);
            return $this->success('projectOptions', $projectOptions, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function singleProject($slug)
    {
        try {
            if (Project::where('slug', $slug)->exists()) {
                $project = Project::where('slug', $slug)->first();
                // $paymentRow = [];
                // foreach($project->mPaymentPlans as $payment){
                //     array_push($paymentRow,
                //     ['title'=>$payment->value, 'rows'=>$payment->paymentPlans]
                //     );
                // }

                $singleProject = (object)[];

                $latitude = $project->address_latitude;
                $longitude = $project->address_longitude;

                // $nearbyProjects = array();
                if ($longitude &&  $latitude) {


                    //                  $nearbyProjects = Project::where('slug','!=', $slug)->selectRaw('*, ( 6367 * acos( cos( radians( ? ) ) * cos( radians( address_latitude ) ) * cos( radians( address_longitude ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( address_latitude ) ) ) ) AS distance', [$latitude, $longitude, $latitude])
                    // ->having('distance', '<', 20)
                    // ->orderBy('distance')
                    // ->get();


                    $nearbyProjects =  DB::select(DB::raw("select id, slug, ( 6367 * acos( cos( radians($latitude) ) * cos( radians(address_latitude ) ) * cos( radians( address_longitude ) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians( address_latitude ) ) ) ) AS distance from `projects` 
    where `projects`.`deleted_at` is null  and  
    `projects`.`slug` <> '$slug' and
    `projects`.`status` = 'active'
    having `distance` < 20 order by `distance` asc limit 0,12;"));

                    //     $nearbyProjects = Project::select(DB::raw("id, ( 6367 * acos( cos( radians(25.0898909) ) * cos( radians(address_latitude ) ) * cos( radians( address_longitude ) - radians(55.1441655) ) + sin( radians(25.0898909) ) * sin( radians( address_latitude ) ) ) ) AS distance"))->having('distance', '<', 20)->orderBy('distance')
                    // ->get();    



                    // $nearbyProjects = Project::active();
                    // $nearbyProjects = $nearbyProjects->select("*", DB::raw("6371 * acos(cos(radians(" . $latitude . "))* cos(radians(address_latitude)) * cos(radians(address_longitude) - radians(" . $longitude . ")) + sin(radians(" . $latitude . ")) * sin(radians(address_latitude))) AS distance"));

                    // $nearbyProjects = $nearbyProjects->having('distance', '<', 50000)->orderBy('distance', 'asc');
                    // $nearbyProjects = $nearbyProjects->where('slug','!=', $slug)->take(12)->get()->map(function($project){
                    //     return [
                    //         'id'=>"nearbyProject_".$project->id,
                    //         'title' => $project->title,
                    //         'slug' => $project->slug,
                    //         'mainImage' => $project->mainImage,
                    //         'accommodation'=>$project->accommodation ? $project->accommodation->name :''
                    //     ];    
                    // });
                }

                // $singleProject->nearbyProjects = $nearbyProjects;
                return $this->success('Single Project', $nearbyProjects, 200);


                $singleProject->id = $project->id;
                $singleProject->name = $project->title;
                $singleProject->payment = $paymentRow;
                $singleProject->sub_title_1 = $project->sub_title;
                $singleProject->sub_title_2 = $project->sub_title_1;
                $singleProject->slug = $project->slug;
                $singleProject->address_latitude = $project->address_latitude;
                $singleProject->address_longitude = $project->address_longitude;
                $singleProject->bedrooms = $project->bedrooms;


                $minBed = $project->subProjects->min('bedrooms');
                $maxBed = $project->subProjects->max('bedrooms');
                if ($minBed != $maxBed) {
                    $bedroom = $minBed . "-" . $maxBed;
                } else {
                    $bedroom = $minBed;
                }

                $singleProject->availableUnits =  $bedroom . "BR";
                $area = 0;
                $starting_price = 0;
                if (count($project->subProjects) > 0) {
                    $area =  $project->subProjects->where('area', $project->subProjects->min('area'))->first()->area;
                    $starting_price = $project->subProjects->where('starting_price', $project->subProjects->min('starting_price'))->first()->starting_price;
                }
                $singleProject->area =  $area;

                $minArea = $project->subProjects->min('area');
                $maxArea = $project->subProjects->max('area');

                if ($minArea != $maxArea) {
                    $areaAvailable = $minArea . "-" . $maxArea;
                } else {
                    $areaAvailable = $minArea;
                }


                $areaUnit = $project->area_unit ? $project->area_unit : 'Sq.Ft';

                $singleProject->areaAvailable = $areaAvailable;

                $singleProject->areaUnit = $areaUnit;
                $singleProject->mainImage = $project->mainImage;
                $singleProject->interiorGallery = $project->interiorGallery;
                $singleProject->exteriorGallery = $project->exteriorGallery;
                $singleProject->price = $starting_price;
                // $singleProject->handOver =$project->completion_date;

                $dateStr = $project->completion_date;
                //Get the month number of the date
                //in question.
                $month = date("n", strtotime($dateStr));

                //Divide that month number by 3 and round up
                //using ceil.
                $yearQuarter = ceil($month / 3);
                $singleProject->handOver = "Q" . $yearQuarter . " " . date("Y", strtotime($dateStr));


                $singleProject->brochure = $project->brochure;
                $singleProject->shortDescription = $project->short_description->render();
                $singleProject->longDescription = $project->long_description->render();
                $singleProject->hightlightDescription = $project->features_description->render();
                if (count($project->subProjects) > 0) {
                    $singleProject->minPrice = $project->subProjects->where('starting_price', $project->subProjects->min('starting_price'))->first()->starting_price;
                    $singleProject->maxPrice = $project->subProjects->where('starting_price', $project->subProjects->max('starting_price'))->first()->starting_price;
                } else {
                    $singleProject->minPrice = 0;
                    $singleProject->maxPrice = 0;
                }


                $nearbyProjects = array();
                if ($longitude &&  $latitude) {
                    $nearbyProjects = Project::active();
                    $nearbyProjects = $nearbyProjects->select("*", DB::raw("6371 * acos(cos(radians(" . $latitude . "))* cos(radians(address_latitude)) * cos(radians(address_longitude) - radians(" . $longitude . ")) + sin(radians(" . $latitude . ")) * sin(radians(address_latitude))) AS distance"));

                    $nearbyProjects = $nearbyProjects->having('distance', '<', 50000);

                    $nearbyProjects = $nearbyProjects->orderBy('distance', 'asc');
                    $nearbyProjects = $nearbyProjects->where('slug', '!=', $slug)->take(12)->get()->map(function ($project) {
                        return [
                            'id' => "nearbyProject_" . $project->id,
                            'title' => $project->title,
                            'slug' => $project->slug,
                            'mainImage' => $project->mainImage,
                            'accommodation' => $project->accommodation ? $project->accommodation->name : ''
                        ];
                    });
                }

                $singleProject->nearbyProjects = $nearbyProjects;

                $singleProject->types = $project->subProjects->map(function ($subProject) {

                    if (Property::where('sub_project_id', $subProject->id)->active()->exists()) {
                        $property =  Property::where('sub_project_id', $subProject->id)->active()->first()->slug;
                    } else {
                        $property = null;
                    }
                    return [
                        'id' => "type_" . $subProject->id,
                        'name' => $subProject->title,
                        'bedrooms' => $subProject->bedrooms,
                        'startingPrice' => $subProject->starting_price,
                        'area' => $subProject->area,
                        'areaUnit' => $subProject->area_unit ? $subProject->area_unit : 'Sq.Ft',
                        'accommodation' => $subProject->accommodation ? $subProject->accommodation->name : '',
                        'floorPlan' => $subProject->floorPlan,
                        'property' => $property,
                        'paymentPlans' => $subProject->paymentPlans->map(function ($payment) {
                            return [
                                'id' => "payment_" . $payment->id,
                                'installment' => $payment->name,
                                'percentage' => $payment->key,
                                'milestone' => $payment->value
                            ];
                        }),
                    ];
                });

                $singleProject->rentProperties = Property::where('project_id', $project->id)->where('category_id', 9)->active()->latest()->limit(8)->get()->map(function ($property) {

                    if ($property->property_source == "crm") {
                        $banner = $property->mainImage;
                    } else {
                        $banner = $property->property_banner;
                    }

                    return [
                        'id' => "rentProperty_" . $property->id,
                        'name' => $property->name,
                        'slug' => $property->slug,
                        'bedrooms' => $property->bedrooms,
                        'area' => $property->area,
                        'unit_measure' => $property->unit_measure,
                        'bathrooms' => $property->bathrooms,
                        'price' => $property->price,
                        'mainImage' =>  $banner,
                        'accommodation' => $property->accommodations ? $property->accommodations->name : ''
                    ];
                });

                $singleProject->buyProperties = Property::where('project_id', $project->id)->where('category_id', 8)->active()->latest()->limit(8)->get()->map(function ($property) {
                    if ($property->property_source == "crm") {
                        $banner = $property->mainImage;
                    } else {
                        $banner = $property->property_banner;
                    }

                    return [
                        'id' => "buyProperty_" . $property->id,
                        'name' => $property->name,
                        'slug' => $property->slug,
                        'bedrooms' => $property->bedrooms,
                        'bathrooms' => $property->bathrooms,
                        'area' => $property->area,
                        'unit_measure' => $property->unit_measure,
                        'price' => $property->price,
                        'mainImage' => $banner,
                        'accommodation' => $property->accommodations ? $property->accommodations->name : ''
                    ];
                });


                $developer = (object) [];
                if ($project->developer) {
                    $developer->name = $project->developer->name;
                    $developer->slug = $project->developer->slug;
                    $developer->logo = $project->developer->logo;
                    $developer->description = $project->developer->short_description->render();
                }
                $singleProject->developer = $developer;

                $singleProject->highlights = $project->highlights->map(function ($highlight) {
                    return [
                        'id' => "highlight_" . $highlight->id,
                        'name' => $highlight->name,
                        'image' => $highlight->image
                    ];
                });

                if ($project->meta_title) {
                    $singleProject->title = $project->meta_title;
                } else {
                    $singleProject->title = WebsiteSetting::getSetting('website_name') ? WebsiteSetting::getSetting('website_name') : '';
                }
                if ($project->meta_description) {
                    $singleProject->meta_description = $project->meta_description;
                } else {
                    $singleProject->meta_description = WebsiteSetting::getSetting('description') ? WebsiteSetting::getSetting('description') : '';
                }

                if ($project->meta_keywords) {
                    $singleProject->meta_keywords = $project->meta_keywords;
                } else {
                    $singleProject->meta_keywords = WebsiteSetting::getSetting('keywords') ? WebsiteSetting::getSetting('keywords') : '';
                }


                return $this->success('Single Project', $singleProject, 200);
            } else {
                return $this->success('Single Project', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function singleProjectDetail($slug)
    {
        try {
            if (Project::where('slug', $slug)->exists()) {

                $currencyINR = null;
                if (WebsiteSetting::where('key', config('constants.INR_Currency'))->exists()) {
                    $currencyINR = WebsiteSetting::getSetting(config('constants.INR_Currency')) ? WebsiteSetting::getSetting(config('constants.INR_Currency')) : '';
                }

                $singleProject = new SingleProjectResource(Project::with([
                    'subProjects' => function ($query) {
                        return $query->active()->get();
                    },
                    'subProjects.accommodation',
                    'mPaymentPlans',
                    'developer',
                    'mainCommunity'
                ])->where('slug', $slug)->first(), $currencyINR);

                return $this->success('Single Project', $singleProject, 200);
            } else {
                return $this->success('Single Project', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function nearByProjects($slug)
    {
        try {
            if (Project::where('slug', $slug)->exists()) {
                $project = Project::where('slug', $slug)->first();
                $latitude = $project->address_latitude;
                $longitude = $project->address_longitude;
                $nearbyProjects = array();
                if ($latitude && $longitude) {
                    $nearbyProjects = DB::select(DB::raw("select id, slug, ( 6367 * acos( cos( radians($latitude) ) * cos( radians(address_latitude ) ) * cos( radians( address_longitude ) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians( address_latitude ) ) ) ) AS distance from `projects` 
                        where `projects`.`deleted_at` is null  and  
                        `projects`.`slug` <> '$project->slug' and
                        `projects`.`status` = 'active' and `projects`.`is_approved` = 'approved'
                        having `distance` < 20 order by `distance` asc limit 0,12;"));


                    $nearbyProjects = NearByProjectsResource::collection(Project::active()->approved()->whereIn('id', Arr::pluck($nearbyProjects, 'id'))->get());
                }

                return $this->success('Single Project', $nearbyProjects, 200);
            } else {
                return $this->success('Single Project', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function projectMeta($slug)
    {
        try {
            if (Project::where('slug', $slug)->exists()) {
                $project = Project::where('slug', $slug)->first();
                $singleProject = (object)[];
                if ($project->meta_title) {
                    $singleProject->title = $project->meta_title;
                } else {
                    $singleProject->title = WebsiteSetting::getSetting('website_name') ? WebsiteSetting::getSetting('website_name') : '';
                }
                if ($project->meta_description) {
                    $singleProject->meta_description = $project->meta_description;
                } else {
                    $singleProject->meta_description = WebsiteSetting::getSetting('description') ? WebsiteSetting::getSetting('description') : '';
                }

                if ($project->meta_keywords) {
                    $singleProject->meta_keywords = $project->meta_keywords;
                } else {
                    $singleProject->meta_keywords = WebsiteSetting::getSetting('keywords') ? WebsiteSetting::getSetting('keywords') : '';
                }
                return $this->success('Single Project Meta', $singleProject, 200);
            } else {
                return $this->success('Single Project', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function projectsList(Request $request)
    {
        try {

            $developers = [];
            $communities = [];
            $projectArrays = [];
            if (isset($request->searchBy)) {

                foreach (json_decode($request->searchBy, true) as $search) {

                    $typeVaribale = explode("-", $search['type']);

                    if ($typeVaribale[0] == "developer") {
                        array_push($developers, $typeVaribale[1]);
                    } elseif ($typeVaribale[0] == "community") {
                        array_push($communities, $typeVaribale[1]);
                    } elseif ($typeVaribale[0] == "project") {
                        array_push($projectArrays, $typeVaribale[1]);
                    }
                }
            }
            $collection = Project::with('subProjects', 'accommodation', 'mainCommunity')->approved()->active()->mainProject();


            $collection->where(function ($query) use ($projectArrays, $developers, $communities) {

                if (!empty($projectArrays)) {
                    $query->orWhereIn('id', $projectArrays);
                }

                if (!empty($developers)) {
                    $query->orWhereIn('developer_id', $developers);
                }

                if (!empty($communities)) {
                    $query->orWhereIn('community_id', $communities);
                }
            });

            // if(count($projectArrays) > 0 )
            // {
            //     $collection->orWhereIn('id', $projectArrays);
            // }
            // if(count($communities) > 0)
            // {
            //     $collection->orWhereIn('community_id', $communities);
            // }
            // if(count($developers) > 0)
            // {
            //      $collection->orWhereIn('developer_id', $developers);
            // }

            if (isset($request->completion_status_id)) {
                $collection->where('completion_status_id', $request->completion_status_id);
            }
            if (isset($request->amenity_id)) {
                $amenity = $request->amenity_id;
                $collection->whereHas('amenities', function ($query) use ($amenity) {
                    $query->where('amenities.id', $amenity);
                });
            }
            if (isset($request->isCommercial)) {
                $commericalAcc = Accommodation::whereIn('type', ['Both', 'Commercial'])->active()->approved()->pluck('id')->toArray();
                $collection->whereIn('accommodation_id', $commericalAcc);
            }

            if (isset($request->accommodation_id)) {
                $collection->where('accommodation_id', $request->accommodation_id);
            }

            if (isset($request->community)) {
                $collection->where('community_id', $request->community);
            }
            if (isset($request->bedrooms)) {
                $bedrooms = $request->bedrooms;
                $collection->whereHas('subProjects', function ($query) use ($bedrooms) {
                    $query->where('bedrooms', 'like', '%' . $bedrooms . '%');
                });
            }
            if (isset($request->bathroom)) {
                $collection->where('bedrooms', $request->bathroom);
            }
            if (isset($request->area)) {
                $collection->where('area', $request->area);
            }

            if (isset($request->minarea) || isset($request->maxarea)) {

                $minarea = $request->minarea;
                $maxarea = $request->maxarea;

                if (isset($request->minarea)) {
                    $collection->whereHas('subProjects', function ($query) use ($minarea) {
                        $query->where('area', '>', (int)$minarea);
                    });
                } elseif (isset($request->maxarea)) {

                    $collection->whereHas('subProjects', function ($query) use ($maxarea) {
                        $query->where('area', '<', (int)$maxarea);
                    });
                }
                if (isset($request->minarea) && isset($request->maxarea)) {

                    $collection->whereHas('subProjects', function ($query) use ($maxarea, $minarea) {
                        $query->whereBetween('area', [(int)$minarea, (int)$maxarea]);
                    });
                }
            }
            if (isset($request->amenities)) {
                $amenity = explode(',', $request->amenities);

                $collection->whereHas('amenities', function ($query) use ($amenity) {
                    $query->whereIn('amenities.id', $amenity);
                });
            }


            if (isset($request->exclusive)) {
                $collection->where('exclusive', $request->exclusive);
            }

            if (isset($request->minprice) || isset($request->maxprice)) {
                $minPrice = $request->minprice;
                $maxprice = $request->maxprice;

                if (isset($request->minprice) && !isset($request->maxprice)) {
                    $collection->whereHas('subProjects', function ($query) use ($minPrice) {
                        $query->where('starting_price', '>', (int)$minPrice);
                    });
                } elseif (isset($request->maxprice) && !isset($request->minprice)) {

                    $collection->whereHas('subProjects', function ($query) use ($maxprice) {
                        $query->where('starting_price', '<', (int)$maxprice);
                    });
                }
                if (isset($request->minprice) && isset($request->maxprice)) {
                    // $collection->whereHas('subProjects', function ($query) {
                    //     $query->whereRaw('CAST(starting_price AS DECIMAL(10, 2)) BETWEEN 1000000 AND 40000000')
                    //           ->whereNull('deleted_at');
                    // });

                    $collection->whereHas('subProjects', function ($query) use ($maxprice, $minPrice) {
                        //$query->whereBetween('starting_price', [(int)$minPrice,(int)$maxprice]);
                        $query->whereRaw("CAST(starting_price AS DECIMAL(10, 2)) BETWEEN $minPrice AND $maxprice")->whereNull('deleted_at');
                    });
                }
            }
            // if (isset($request->sortBy)) {
            //     if ($request->sortBy == 2) {
            //         $collection->orderBy('price', 'asc');
            //     } elseif ($request->sortBy == 3) {
            //         $collection->orderBy('price', 'desc');
            //     }
            // } else {
            //     $collection->orderBy("id");
            // }


            if ($request->coordinates) {
                $allPolygons = $request->coordinates;
                $polygons = [];
                foreach ($allPolygons as $coordinates) {
                    $polygon = '((';

                    foreach ($coordinates as $coord) {
                        $polygon .= $coord['lng'] . ' ' . $coord['lat'] . ',';
                    }
                    $polygon = rtrim($polygon, ',') . ',' . $coordinates[0]['lng'] . ' ' . $coordinates[0]['lat'] . '))';
                    $polygons[] = $polygon;
                }
                $multiPolygonString = 'MULTIPOLYGON(' . implode(',', $polygons) . ')';
                $collection->whereRaw("ST_Within(Point(address_longitude, address_latitude), ST_GeomFromText(?))", [$multiPolygonString]);
            }
            $projects = $collection->orderByRaw('ISNULL(projectOrder)')->orderBy('projectOrder', 'asc')->paginate(1000);
            $projects = $projects->appends(request()->query());


            // $projects=$projects->toJson();

            return $this->success('Projects', ProjectListResource::collection($projects)->response()->getData(true), 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function projects(Request $request)
    {

        try {
            
            $developers = [];
            $communities = [];
            $projectArrays = [];


            $currencyINR = null;
            if (WebsiteSetting::where('key', config('constants.INR_Currency'))->exists()) {
                $currencyINR = WebsiteSetting::getSetting(config('constants.INR_Currency')) ? WebsiteSetting::getSetting(config('constants.INR_Currency')) : '';
            }

            if(isset($request->currency)){
                $currenyExist = Currency::where('name', $request->currency)->exists();

                if($currenyExist){
                    $currency = Currency::where('name', $request->currency)->first()->value;
                }
                if (isset($request->minprice)) {
                    $request->merge([
                        'minprice' => $request->minprice / $currency
                    ]);

                }

                if (isset($request->maxprice)) {
                    $request->merge([
                        'maxprice' => $request->maxprice / $currency
                    ]);

                }
            }
            
            if (isset($request->searchBy)) {

                foreach (json_decode($request->searchBy, true) as $search) {

                    $typeVaribale = explode("-", $search['type']);

                    if ($typeVaribale[0] == "developer") {
                        array_push($developers, $typeVaribale[1]);
                    } elseif ($typeVaribale[0] == "community") {
                        array_push($communities, $typeVaribale[1]);
                    } elseif ($typeVaribale[0] == "project") {
                        array_push($projectArrays, $typeVaribale[1]);
                    }
                }
            }
            $collection = Project::with(['subProjects', 'accommodation', 'mainCommunity', 'amenities'])->approved()->active()->mainProject();


            $collection->where(function ($query) use ($projectArrays, $developers, $communities) {

                if (!empty($projectArrays)) {
                    $query->orWhereIn('id', $projectArrays);
                }

                if (!empty($developers)) {
                    $query->orWhereIn('developer_id', $developers);
                }

                if (!empty($communities)) {
                    $query->orWhereIn('community_id', $communities);
                }
            });

            // if(count($projectArrays) > 0 )
            // {
            //     $collection->orWhereIn('id', $projectArrays);
            // }
            // if(count($communities) > 0)
            // {
            //     $collection->orWhereIn('community_id', $communities);
            // }
            // if(count($developers) > 0)
            // {
            //      $collection->orWhereIn('developer_id', $developers);
            // }

            if (isset($request->completion_status_id)) {
                $collection->where('completion_status_id', $request->completion_status_id);
            }
            if (isset($request->amenity_id)) {
                $amenity = $request->amenity_id;
                $collection->whereHas('amenities', function ($query) use ($amenity) {
                    $query->where('amenities.id', $amenity);
                });
            }
            if (isset($request->isCommercial)) {
                // $commericalAcc =Accommodation::whereIn('type', ['Both', 'Commercial'])->active()->approved()->pluck('id')->toArray();
                // $collection->whereIn('accommodation_id', $commericalAcc);

                $collection->whereIn('used_for', ['Both', 'Commercial']);
            }

            if (isset($request->accommodation_id)) {
                $collection->where('accommodation_id', $request->accommodation_id);
            }

            if (isset($request->community)) {
                $collection->where('community_id', $request->community);
            }
            if (isset($request->bedrooms)) {
                $bedrooms = $request->bedrooms;
                $collection->whereHas('subProjects', function ($query) use ($bedrooms) {
                    $query->where('bedrooms', 'like', '%' . $bedrooms . '%');
                });
            }
            if (isset($request->bathroom)) {
                $collection->where('bedrooms', $request->bathroom);
            }
            if (isset($request->area)) {
                $collection->where('area', $request->area);
            }

            if (isset($request->minarea) || isset($request->maxarea)) {

                $minarea = $request->minarea;
                $maxarea = $request->maxarea;

                if (isset($request->minarea)) {
                    $collection->whereHas('subProjects', function ($query) use ($minarea) {
                        $query->where('area', '>', (int)$minarea);
                    });
                } elseif (isset($request->maxarea)) {

                    $collection->whereHas('subProjects', function ($query) use ($maxarea) {
                        $query->where('area', '<', (int)$maxarea);
                    });
                }
                if (isset($request->minarea) && isset($request->maxarea)) {

                    $collection->whereHas('subProjects', function ($query) use ($maxarea, $minarea) {
                        $query->whereBetween('area', [(int)$minarea, (int)$maxarea]);
                    });
                }
            }
            if (isset($request->amenities)) {
                $amenity = explode(',', $request->amenities);

                $collection->whereHas('amenities', function ($query) use ($amenity) {
                    $query->whereIn('amenities.id', $amenity);
                });
            }


            if (isset($request->exclusive)) {
                $collection->where('exclusive', $request->exclusive);
            }

            if (isset($request->minprice) || isset($request->maxprice)) {
                $minPrice = $request->minprice;
                $maxprice = $request->maxprice;

                if (isset($request->minprice) && !isset($request->maxprice)) {
                    $collection->whereHas('subProjects', function ($query) use ($minPrice) {
                        $query->where('starting_price', '>', (int)$minPrice);
                    });
                } elseif (isset($request->maxprice) && !isset($request->minprice)) {

                    $collection->whereHas('subProjects', function ($query) use ($maxprice) {
                        $query->where('starting_price', '<', (int)$maxprice);
                    });
                }
                if (isset($request->minprice) && isset($request->maxprice)) {
                    $collection->whereHas('subProjects', function ($query) use ($maxprice, $minPrice) {
                        //$query->whereBetween('starting_price', [(int)$minPrice,(int)$maxprice]);
                        $query->whereRaw("CAST(starting_price AS DECIMAL(10, 2)) BETWEEN $minPrice AND $maxprice")->whereNull('deleted_at');
                    });
                }
            }
            // if (isset($request->sortBy)) {
            //     if ($request->sortBy == 2) {
            //         $collection->orderBy('price', 'asc');
            //     } elseif ($request->sortBy == 3) {
            //         $collection->orderBy('price', 'desc');
            //     }
            // } else {
            //     $collection->orderBy("id");
            // }


            if ($request->coordinates) {
                $allPolygons = $request->coordinates;
                $polygons = [];
                foreach ($allPolygons as $coordinates) {
                    $polygon = '((';

                    foreach ($coordinates as $coord) {
                        $polygon .= $coord['lng'] . ' ' . $coord['lat'] . ',';
                    }
                    $polygon = rtrim($polygon, ',') . ',' . $coordinates[0]['lng'] . ' ' . $coordinates[0]['lat'] . '))';
                    $polygons[] = $polygon;
                }
                $multiPolygonString = 'MULTIPOLYGON(' . implode(',', $polygons) . ')';
                $collection->whereRaw("ST_Within(Point(address_longitude, address_latitude), ST_GeomFromText(?))", [$multiPolygonString]);
            }

            $amenities = $collection->get()->flatMap->amenities->unique('id');

            $projects = $collection->orderByRaw('ISNULL(projectOrder)')->orderBy('projectOrder', 'asc')->paginate(1000);
            $projects = $projects->appends(request()->query());


            return $this->success(
                'Projects',
                [
                    //'projects' => ProjectListResource::collection($projects)->response()->getData(true),
                    'projects' => new ProjectCollection($projects, $currencyINR),
                    'amenities' =>  AmenitiesNameResource::collection($amenities),

                ],
                200
            );
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }



        // try{

        //     $developers = [];
        //   $communities = [];
        //   $projectArrays = [];
        //   if(isset($request->searchBy)){
        //       // dd($request->searchBy);
        //       foreach(json_decode($request->searchBy, true) as $search){

        //           $typeVaribale = explode("-", $search['type']);

        //           if($typeVaribale[0] == "developer"){
        //               array_push($developers, $typeVaribale[1]);
        //           }elseif($typeVaribale[0] == "community"){
        //               array_push($communities, $typeVaribale[1]);
        //           }elseif($typeVaribale[0] == "project"){
        //               array_push($projectArrays, $typeVaribale[1]);
        //           }
        //       }
        //   }
        //   // dd($developers);


        //     $collection = Project::query()->approved()->active()->mainProject();

        //     // if(isset($request->category)){
        //     //     if($request->category == 'rent'){
        //     //         $categoryName = "Rent";
        //     //         $collection->where('category_id', 9);
        //     //     }elseif($request->category == 'buy'){
        //     //         $collection->where('category_id', 8);
        //     //         $categoryName = "Buy";
        //     //     }
        //     // }
        //     if(count($projectArrays) > 0 )
        //     {
        //         $collection->whereIn('id', $projectArrays);
        //     }
        //     if(count($communities) > 0)
        //     {
        //         $collection->whereIn('community_id', $communities);
        //     }
        //     if(count($developers) > 0)
        //     {
        //          $collection->whereIn('developer_id', $developers);
        //     }

        //     if (isset($request->completion_status_id )) {
        //         $collection->where('completion_status_id', $request->completion_status_id );
        //     }
        //     if (isset($request->amenity_id)) {
        //         $amenity = $request->amenity_id;
        //         $collection->whereHas('amenities', function ($query) use ($amenity) {
        //             $query->where('amenities.id', $amenity);
        //         });

        //     }
        //     if (isset($request->accommodation_id)) {
        //         $collection->where('accommodation_id', $request->accommodation_id);
        //     }

        //     if (isset($request->community)) {
        //         $collection->where('community_id', $request->community);
        //     }
        //     if (isset($request->bedrooms)) {
        //         $collection->where('bedrooms', 'like', '%'.$request->bedrooms.'%' );


        //     }
        //     if (isset($request->bathroom)) {
        //         $collection->where('bedrooms', $request->bathroom);
        //     }
        //     if (isset($request->area)) {
        //         $collection->where('area', $request->area);
        //     }

        //     if (isset($request->minarea) || isset($request->maxarea)) {

        //         $minarea = $request->minarea;
        //         $maxarea = $request->maxarea;

        //         if(isset($request->minarea)){
        //             $collection->whereHas('subProjects', function($query) use ($minarea){
        //                 $query->where('area', '>' ,(int)$minarea);
        //             });

        //         }elseif(isset($request->maxarea)){

        //             $collection->whereHas('subProjects', function($query) use ($maxarea){
        //                 $query->where('area', '<' ,(int)$maxarea);
        //             });
        //         }
        //         if(isset($request->minarea) && isset($request->maxarea)){

        //             $collection->whereHas('subProjects', function($query) use ($maxarea, $minarea){
        //                 $query->whereBetween('area', [(int)$minarea,(int)$maxarea]);
        //             });
        //         }

        //     }
        //     if (isset($request->amenities)) {
        //         $amenity = explode(',', $request->amenities);

        //         $collection->whereHas('amenities', function ($query) use ($amenity) {
        //             $query->whereIn('amenities.id', $amenity);
        //             });
        //     }


        //     if(isset($request->exclusive)){
        //         $collection->where('exclusive', $request->exclusive);
        //     }

        //     if (isset($request->minprice) || isset($request->maxprice)) {
        //         $minPrice = $request->minprice;
        //         $maxprice = $request->maxprice;

        //         if(isset($request->minprice)){
        //             $collection->whereHas('subProjects', function($query) use ($minPrice){
        //                 $query->where('starting_price', '>' ,(int)$minPrice);
        //             });

        //         }elseif(isset($request->maxprice)){

        //             $collection->whereHas('subProjects', function($query) use ($maxprice){
        //                 $query->where('starting_price', '<' ,(int)$maxprice);
        //             });
        //         }
        //         if(isset($request->minprice) && isset($request->maxprice)){

        //             $collection->whereHas('subProjects', function($query) use ($maxprice, $minPrice){
        //                 $query->whereBetween('starting_price', [(int)$minPrice,(int)$maxprice]);
        //             });
        //         }
        //     }
        //     // if (isset($request->sortBy)) {
        //     //     if ($request->sortBy == 2) {
        //     //         $collection->orderBy('price', 'asc');
        //     //     } elseif ($request->sortBy == 3) {
        //     //         $collection->orderBy('price', 'desc');
        //     //     }
        //     // } else {
        //     //     $collection->orderBy("id");
        //     // }


        //     if($request->coordinates){
        //         $allPolygons = $request->coordinates;
        //         $polygons = [];
        //         foreach ($allPolygons as $coordinates) {
        //             $polygon = '((';

        //             foreach ($coordinates as $coord) {
        //                 $polygon .= $coord['lng'] . ' ' . $coord['lat'] . ',';
        //             }
        //             $polygon = rtrim($polygon, ',') . ',' . $coordinates[0]['lng'] . ' ' . $coordinates[0]['lat'] . '))';
        //             $polygons[] = $polygon;
        //         }
        //         $multiPolygonString = 'MULTIPOLYGON(' . implode(',', $polygons) . ')';
        //         $collection->whereRaw("ST_Within(Point(address_longitude, address_latitude), ST_GeomFromText(?))", [$multiPolygonString]);
        //     }
        //     $projects = $collection->with('accommodation','mainCommunity')->orderByRaw('ISNULL(projectOrder)')->orderBy('projectOrder', 'asc')->get();


        //     foreach ($projects as $key => $value) {

        //         $value->setAttribute('lat',(double)$value->address_latitude);
        //         $value->setAttribute('lng',(double)$value->address_longitude);
        //         $value->setAttribute('accommodationName',$value->accommodation  ?$value->accommodation->name: null);
        //         $value->setAttribute('completionStatusName',$value->completionStatus ?$value->completionStatus->name: null);
        //         $value->setAttribute('starting_price', count( $value->subProjects) > 0 ? $value->subProjects->where('starting_price', $value->subProjects->min('starting_price'))->first()->starting_price : 0);

        //         $minBed = $value->subProjects->min('bedrooms');
        //         $maxBed = $value->subProjects->max('bedrooms');
        //       if($minBed != $maxBed){
        //           $bedroom = $minBed. "-".$maxBed;
        //       }else{
        //             $bedroom = $minBed;
        //       }

        //         $minArea = $value->subProjects->min('area');
        //         $maxArea = $value->subProjects->max('area');

        //       if($minArea != $maxArea){
        //           $areaAvailable = $minArea. "-".$maxArea;
        //       }else{
        //             $areaAvailable = $minArea;
        //       }
        //       $value->area = $areaAvailable;
        //       $value->area_unit = $value->area_unit ? $value->area_unit : 'Sq.Ft';
        //         $value->setAttribute('bedrooms', $bedroom);

        //     }


        //     $projects=$projects->toJson();

        //     return $this->success('Projects',$projects, 200);
        // } catch (\Exception $exception) {
        //     return $this->failure($exception->getMessage());
        // }

    }
    public function getHomeProjects()
    {
        try {
            $projects = Project::with('accommodations')->select('id', 'title')->active()->home()->limit(8)->get();
            return $this->success('Home Projects', $projects, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage(), $exception->getCode());
        }
    }
    public function getNewProjects()
    {
        try {
            $projects = Project::select('id', 'title')->newLunch()->active()->mainProject()->approved()->get();
            return $this->success('Home Projects', $projects, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage(), $exception->getCode());
        }
    }
}
