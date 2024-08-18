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
    Currency
};
use App\Http\Resources\{
    PropertyListResource,
    SinglePropertyResource,
    SinglePropertyResourceR,
    AmenitiesNameResource,
    PropertyCollection,
    SimiliarPropertyCollection
};

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{

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

    public function areaList($type)
    {
        try {

            if ($type == "ready") {
                $results = DB::select("
                        SELECT area
                        FROM properties
                        WHERE deleted_at IS NULL
                        AND status = 'active'
                        AND is_approved = 'approved'
                        AND category_id= 8
                        AND completion_status_id= 286
                        AND area IS NOT NULL
                        AND area REGEXP '^[0-9]+$'
                        GROUP BY area
                        ORDER BY area;
                    ");
            } elseif ($type == "rent") {
                $results = DB::select("
                SELECT area
                        FROM properties
                        WHERE deleted_at IS NULL
                        AND status = 'active'
                        AND category_id = 9
                        AND is_approved = 'approved'
                        AND area IS NOT NULL
                        AND area REGEXP '^[0-9]+$'
                        GROUP BY area
                        ORDER BY area
                    ");
            } elseif ($type == "offplan") {
                $results = DB::select("
                SELECT area
                        FROM properties
                        WHERE deleted_at IS NULL
                        AND status = 'active'
                        AND is_approved = 'approved'
                        AND category_id= 8
                        AND completion_status_id= 287
                        AND area IS NOT NULL
                        AND area REGEXP '^[0-9]+$'
                        GROUP BY area
                        ORDER BY area
                    ");
            } elseif ($type == "buy") {
                $results = DB::select("
                SELECT area
                        FROM properties
                        WHERE deleted_at IS NULL
                        AND status = 'active'
                        AND category_id= 8
                        AND is_approved = 'approved'
                        AND area IS NOT NULL
                        AND area REGEXP '^[0-9]+$'
                        GROUP BY area
                        ORDER BY area
                    ");
            } elseif ($type == "luxuryProperties") {
                $results = DB::select("
                SELECT area
                        FROM properties
                        WHERE deleted_at IS NULL
                        AND status = 'active'
                        AND is_approved = 'approved'
                        AND exclusive = 1
                        AND area IS NOT NULL
                        AND area REGEXP '^[0-9]+$'
                        GROUP BY area
                        ORDER BY area
                    ");
            } else {
                $results = DB::select("
                SELECT area
                        FROM properties
                        WHERE deleted_at IS NULL
                        AND status = 'active'
                        AND is_approved = 'approved'
                        AND area IS NOT NULL
                        AND area REGEXP '^[0-9]+$'
                        GROUP BY area
                        ORDER BY area
                    ");
            }

            // Initialize arrays to store starting prices in words
            $thousands = [];
            $lakhs = [];
            $crores = [];
            $millions = [];
            $billions = [];
            $hundreds = [];

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
            return $this->success('Property Price List', ['formattedNumbers' => $text], 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }


    public function priceList($type)
    {
        try {

            if ($type == "ready") {
                $results = DB::select("
                        SELECT price
                        FROM properties
                        WHERE deleted_at IS NULL
                        AND website_status = 'available'
                        AND category_id= 8
                        AND completion_status_id= 286
                        AND price IS NOT NULL
                        AND price REGEXP '^[0-9]+$'
                        GROUP BY price
                        ORDER BY price;
                    ");
            } elseif ($type == "rent") {
                $results = DB::select("
                SELECT price
                        FROM properties
                        WHERE deleted_at IS NULL
                       
                        AND category_id = 9
                        AND website_status = 'available'
                        AND price IS NOT NULL
                        AND price REGEXP '^[0-9]+$'
                        GROUP BY price
                        ORDER BY price
                    ");
            } elseif ($type == "offplan") {
                $results = DB::select("
                SELECT price
                        FROM properties
                        WHERE deleted_at IS NULL
                        AND website_status = 'available'
                        AND category_id= 8
                        AND completion_status_id= 287
                        AND price IS NOT NULL
                        AND price REGEXP '^[0-9]+$'
                        GROUP BY price
                        ORDER BY price
                    ");
            } elseif ($type == "buy") {
                $results = DB::select("
                SELECT price
                        FROM properties
                        WHERE deleted_at IS NULL
                        AND category_id= 8
                        AND website_status = 'available'
                        AND price IS NOT NULL
                        AND price REGEXP '^[0-9]+$'
                        GROUP BY price
                        ORDER BY price
                    ");
            } elseif ($type == "luxuryProperties") {
                $results = DB::select("
                SELECT price
                        FROM properties
                        WHERE deleted_at IS NULL
                        AND website_status = 'available'
                        AND exclusive = 1
                        AND price IS NOT NULL
                        AND price REGEXP '^[0-9]+$'
                        GROUP BY price
                        ORDER BY price
                    ");
            } else {
                $results = DB::select("
                SELECT price
                        FROM properties
                        WHERE deleted_at IS NULL
                        AND website_status = 'available'
                        AND price IS NOT NULL
                        AND price REGEXP '^[0-9]+$'
                        GROUP BY price
                        ORDER BY price
                    ");
            }

            // Initialize arrays to store starting prices in words
            $thousands = [];
            $lakhs = [];
            $crores = [];
            $millions = [];
            $billions = [];

            // Convert each starting price to words and categorize them
            foreach ($results as $row) {
                $startingPrice = (int)$row->price;
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
            return $this->success('Property Price List', ['formattedNumbers' => $text], 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }

    public function oldpriceList($type)
    {
        try {
            if ($type == "ready") {
                $results = DB::select("
                        SELECT MIN(price) AS min_price, MAX(price) AS max_price
                        FROM properties
                        WHERE deleted_at IS NULL
                        AND status = 'active'
                        AND is_approved = 'approved'
                        AND completion_status_id= 286
                        AND price IS NOT NULL
                        AND price REGEXP '^[0-9]+$'
                    ");
            } elseif ($type == "rent") {
                $results = DB::select("
                        SELECT MIN(price) AS min_price, MAX(price) AS max_price
                        FROM properties
                        WHERE deleted_at IS NULL
                        AND status = 'active'
                        AND category = 9
                        AND is_approved = 'approved'
                        AND price IS NOT NULL
                        AND price REGEXP '^[0-9]+$'
                    ");
            } elseif ($type == "offplan") {
                $results = DB::select("
                        SELECT MIN(price) AS min_price, MAX(price) AS max_price
                        FROM properties
                        WHERE deleted_at IS NULL
                        AND status = 'active'
                        AND is_approved = 'approved'
                        AND completion_status_id= 287
                        AND price IS NOT NULL
                        AND price REGEXP '^[0-9]+$'
                    ");
            } elseif ($type == "buy") {
                $results = DB::select("
                        SELECT MIN(price) AS min_price, MAX(price) AS max_price
                        FROM properties
                        WHERE deleted_at IS NULL
                        AND status = 'active'
                        AND category_id= 8
                        AND is_approved = 'approved'
                        AND price IS NOT NULL
                        AND price REGEXP '^[0-9]+$'
                    ");
            } elseif ($type == "luxuryProperties") {
                $results = DB::select("
                        SELECT MIN(price) AS min_price, MAX(price) AS max_price
                        FROM properties
                        WHERE deleted_at IS NULL
                        AND status = 'active'
                        AND is_approved = 'approved'
                        AND exclusive = 1
                        AND price IS NOT NULL
                        AND price REGEXP '^[0-9]+$'
                    ");
            } else {
                $results = DB::select("
                        SELECT MIN(price) AS min_price, MAX(price) AS max_price
                        FROM properties
                        WHERE deleted_at IS NULL
                        AND status = 'active'
                        AND is_approved = 'approved'
                        AND price IS NOT NULL
                        AND price REGEXP '^[0-9]+$'
                    ");
            }


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
            return $this->success('Property Price List', $data, 200);
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

    public function singleProperty($slug)
    {
        try {
            if (Property::where('slug', $slug)->exists()) {
                $property = Property::with(['amenities'])
                    ->where('slug', $slug)
                    ->first();

                $longitude = $property->address_latitude;
                $latitude = $property->address_longitude;

                $singleProperty = (object)[];

                $singleProperty->id = $property->id;
                $singleProperty->name = $property->name;
                $singleProperty->permit_number = $property->permit_number;
                $singleProperty->reference_number = $property->reference_number;
                $singleProperty->slug = $property->slug;
                $singleProperty->youtube_video = $property->youtube_video ? $property->youtube_video : 'https://www.youtube.com/watch?v=-6jlrq7idl8&list=PLiPk70af-7kf5A4vVxIWXr1yMaaoBTOb4';
                $singleProperty->address_latitude = $property->address_latitude;
                $singleProperty->address_longitude = $property->address_longitude;

                $singleProperty->default_latitude = $property->communities->address_latitude ?  $property->communities->address_latitude : 25.2048;
                $singleProperty->default_longitude = $property->communities->address_longitude ? $property->communities->address_longitude : 55.2708;


                if ($property->bedrooms == 'ST') {
                    $singleProperty->bedrooms = "Studio";
                } else {
                    if ($property->bedrooms > 1) {
                        $singleProperty->bedrooms = $property->bedrooms . ' Bedrooms';
                    } else {
                        $singleProperty->bedrooms = $property->bedrooms . ' Bedroom';
                    }
                }

                if ($property->bathrooms > 1) {
                    $singleProperty->bathrooms = $property->bathrooms . ' Bathrooms';
                } else {
                    $singleProperty->bathrooms = $property->bathrooms . ' Bathroom';
                }


                $singleProperty->parking = $property->parking_space;
                $singleProperty->area = $property->area;

                $singleProperty->unit_measure = $property->unit_measure;

                $singleProperty->accommodation = $property->accommodations ?  $property->accommodations->name : '';
                $singleProperty->saleOffer = $property->saleOffer;
                $floorplans = [];
                if ($property->subProject && $property->subProject->floorPlan) {
                    $floorplans = $property->subProject->floorPlan;
                }
                $singleProperty->floorplans = $floorplans;
                $singleProperty->video = $property->video;
                $singleProperty->price = $property->price;
                $singleProperty->type = $property->category ?  $property->category->name : '';

                $singleProperty->description = $property->description->render();

                $developer = (object) [];
                if ($property->developer) {
                    $developer->name = $property->developer->name;
                    $developer->slug = $property->developer->slug;
                }
                $singleProperty->developer = $developer;
                $community = (object)[];

                if ($property->project && $property->project->mainCommunity) {

                    $community->address_latitude = $property->project->mainCommunity->address_latitude ?  $property->project->mainCommunity->address_latitude : 25.2048;
                    $community->address_longitude = $property->project->mainCommunity->address_longitude ? $property->project->mainCommunity->address_longitude : 55.2708;
                    $community->name =  $property->project->mainCommunity->name;
                    $community->slug =  $property->project->mainCommunity->slug;
                    $community->gallery =  $property->project->mainCommunity->imageGallery;
                    $community->description =  $property->project->mainCommunity->shortDescription;
                }
                $singleProperty->community = $community;

                $project = (object)[];


                if ($property->project) {
                    $project->name =  $property->project->title;
                    $project->slug =  $property->project->slug;
                    $project->image =  $property->project->mainImage;
                    $project->description =  $property->project->short_description->render();
                }
                $singleProperty->project = $project;

                $amenities = (object)[];
                if ($property->project && $property->project->amenities) {

                    $amenities = $property->project->amenities->map(function ($amenity) {
                        return [
                            'id' => "amenity_" . $amenity->id,
                            'name' => $amenity->name,
                            'image' => $amenity->image
                        ];
                    })->take(8);

                    // $singleProperty->amenities = $property->project->mainCommunity->amenities->map(function($amenity){
                    //     return [
                    //         'id'=>"amenity_".$amenity->id,
                    //          'name'=>$amenity->name,
                    //          'image'=> $amenity->image
                    //         ];
                    // })->take(8);

                }
                $singleProperty->amenities = $amenities;


                if ($property->property_source == "xml") {
                    $singleProperty->gallery = $property->propertygallery->map(function ($img) {
                        return [
                            'id' => "gallery_" . $img->id,
                            'path' => $img->galleryimage
                        ];
                    });
                } else {

                    $singleProperty->gallery = $property->subImages;
                }


                $agent = (object)[];
                if ($property->agent) {
                    $agent->name =  $property->agent->name;
                    $agent->email =  $property->agent->email;
                    $agent->slug = $property->agent->slug;
                    $agent->whatsapp =  $property->agent->whatsapp_number;
                    $agent->contact =  $property->agent->contact_number;
                    $agent->image =  $property->agent->image;
                    $agent->designation = $property->agent->designation;
                }
                $singleProperty->agent = $agent;
                $singleProperty->category = $property->category->name;
                $singleProperty->similarProperties = Property::where('slug', '!=', $slug)->where('category_id', $property->category_id)->active()->latest()->limit(8)->get()->map(function ($similarProperty) {

                    if ($similarProperty->property_source == "crm") {
                        $banner = $similarProperty->mainImage;
                    } else {
                        $banner = $similarProperty->property_banner;
                    }

                    return [
                        'id' => 'similar_' . $similarProperty->id,
                        'name' => $similarProperty->name,
                        'slug' => $similarProperty->slug,
                        'price' => $similarProperty->price,
                        'area' => $similarProperty->area,
                        'unit_measure' => $similarProperty->unit_measure,
                        'accommodation' => $similarProperty->accommodations ?  $similarProperty->accommodations->name : '',
                        'property_banner' => $banner,
                        'communityName' =>  $similarProperty->communities->name,
                        'bathrooms' => $similarProperty->bathrooms,
                        'bedrooms' => $similarProperty->bedrooms
                    ];
                });


                if ($property->meta_title) {
                    $singleProperty->title = $property->meta_title;
                } else {
                    $singleProperty->title = WebsiteSetting::getSetting('website_name') ? WebsiteSetting::getSetting('website_name') : '';
                }
                if ($property->meta_description) {
                    $singleProperty->meta_description = $property->meta_description;
                } else {
                    $singleProperty->meta_description = WebsiteSetting::getSetting('description') ? WebsiteSetting::getSetting('description') : '';
                }

                if ($property->meta_keywords) {
                    $singleProperty->meta_keywords = $property->meta_keywords;
                } else {
                    $singleProperty->meta_keywords = WebsiteSetting::getSetting('keywords') ? WebsiteSetting::getSetting('keywords') : '';
                }


                return $this->success('Single Property', $singleProperty, 200);
            } else {
                return $this->success('Single Property', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function singlePropertyDetailR($slug)
    {
        try {
            if (Property::where('slug', $slug)->exists()) {
                $property = Property::with(['completionStatus', 'amenities', 'accommodations', 'subProject', 'category', 'project', 'project.mainCommunity', 'project.developer', 'agent'])
                    ->where('slug', $slug)
                    ->first();

                // $property = Property::where('slug', $slug)
                //     ->first();

                $property = new SinglePropertyResourceR($property);

                return $this->success('Single Property', $property, 200);
            } else {
                return $this->success('Single Property', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function singlePropertyDetail($slug)
    {
        try {
            if (Property::where('slug', $slug)->exists()) {
                $property = Property::with(['amenities', 'completionStatus', 'accommodations', 'subProject', 'category', 'project', 'project.mainCommunity', 'agent'])
                    ->where('slug', $slug)
                    ->first();

                $currencyINR = null;
                if (WebsiteSetting::where('key', config('constants.INR_Currency'))->exists()) {
                    $currencyINR = WebsiteSetting::getSetting(config('constants.INR_Currency')) ? WebsiteSetting::getSetting(config('constants.INR_Currency')) : '';
                }

                $property = new SinglePropertyResource($property, $currencyINR);

                return $this->success('Single Property', $property, 200);
            } else {
                return $this->success('Single Property', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function singlePropertyMeta($slug)
    {
        try {
            if (Property::where('slug', $slug)->exists()) {
                $property = Property::with(['amenities'])
                    ->where('slug', $slug)
                    ->first();
                $singleProperty = (object)[];
                if ($property->meta_title) {
                    $singleProperty->meta_title = $property->meta_title;
                } else {
                    $singleProperty->meta_title = WebsiteSetting::getSetting('website_name') ? WebsiteSetting::getSetting('website_name') : '';
                }
                if ($property->meta_description) {
                    $singleProperty->meta_description = $property->meta_description;
                } else {
                    $singleProperty->meta_description = WebsiteSetting::getSetting('description') ? WebsiteSetting::getSetting('description') : '';
                }

                if ($property->meta_keywords) {
                    $singleProperty->meta_keywords = $property->meta_keywords;
                } else {
                    $singleProperty->meta_keywords = WebsiteSetting::getSetting('keywords') ? WebsiteSetting::getSetting('keywords') : '';
                }

                return $this->success('Single Property Meta', $singleProperty, 200);
            } else {
                return $this->success('Single Property Meta', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function propertiesDemo(Request $request)
    {
        try {


            // Define your raw SQL query
            // $sql = "SELECT * FROM properties WHERE deleted_at is NULL";

            // // Count total records for pagination
            // $total = collect(DB::select("SELECT COUNT(*) as total FROM properties WHERE deleted_at is NULL"))->first()->total;

            // // Define pagination variables
            // $currentPage = LengthAwarePaginator::resolveCurrentPage();
            // $perPage = 15; // Number of items per page
            // $offset = ($currentPage * $perPage) - $perPage;

            // // Modify the query to get only the items for the current page
            // $paginatedItems = DB::select("$sql LIMIT $offset, $perPage");
            //  return $this->success('Properties',$paginatedItems, 200);

            $developers = [];
            $communities = [];
            $projects = [];
            if (isset($request->searchBy)) {
                foreach (json_decode($request->searchBy, true) as $search) {
                    $typeVaribale = explode("-", $search['type']);
                    if ($typeVaribale[0] == "developer") {
                        array_push($developers, $typeVaribale[1]);
                    } elseif ($typeVaribale[0] == "community") {
                        array_push($communities, $typeVaribale[1]);
                    } elseif ($typeVaribale[0] == "project") {
                        array_push($projects, $typeVaribale[1]);
                    }
                }
            }

            // $collection = Property::with('completionStatus', 'accommodations', 'category', 'project')->active()->approved()->available()->whereHas('project', function ($query) {
            //     $query->whereNotNull('permit_number')->where('qr_link',  '!=', '')->whereNotNull('qr_link');
            // });

            $collection = Property::with('completionStatus', 'accommodations', 'category')->available()->where('is_valid', 1);
           

            $currencyINR = null;
            if (WebsiteSetting::where('key', config('constants.INR_Currency'))->exists()) {
                $currencyINR = WebsiteSetting::getSetting(config('constants.INR_Currency')) ? WebsiteSetting::getSetting(config('constants.INR_Currency')) : '';
            }
            
            if(isset($request->currency)){
                $currenyExist = Currency::where('name', $request->currency)->exists();

                if($currenyExist){
                    $currency = Currency::where('name', $request->currency)->first()->value;
                }
                if (isset($request->minprice) || isset($request->maxprice)) {
                    $request->merge([
                        'minprice' => $request->minprice / $currency,
                        'maxprice' => $request->maxprice / $currency
                    ]);

                }
            }
            
            // if(isset($request->currency)){
            //     if($request->currency == 'INR' && isset($request->minprice) || isset($request->maxprice)){
            //         $request['minprice'] =  $request->minprice/$currencyINR;
            //         $request['maxprice'] = $request->maxprice/$currencyINR ;
            //     }
               
            // }
            if (isset($request->category)) {
                if ($request->category == 'rent') {
                    $categoryName = "Rent";
                    $collection->rent();
                } elseif ($request->category == 'buy') {
                    $collection->buy();
                    $categoryName = "Buy";
                }
            }

            if (isset($request->sortBy)) {
                if ($request->sortBy == 2) {
                    $collection->orderBy('price', 'asc');
                } elseif ($request->sortBy == 3) {
                    $collection->orderBy('price', 'desc');
                }
            } else {
                // $collection->orderBy("id");
                $collection->orderByRaw('ISNULL(propertyOrder)')->orderBy('propertyOrder', 'asc');
            }

            if (isset($request->completion_status_id) && $request->completion_status_id != "all") {
                $collection->where('completion_status_id', $request->completion_status_id);
            }
            if (isset($request->amenities)) {
                $amenity = explode(',', $request->amenities);

                $collection->whereHas('amenities', function ($query) use ($amenity) {
                    $query->whereIn('amenities.id', $amenity);
                });

                // $collection->whereIn('project_id',  Project::active()->mainProject()->whereHas('amenities', function ($query) use ($amenity) {
                //     $query->whereIn('amenities.id', $amenity);
                //     })->active()->pluck('id') )->latest()->pluck('id');

            }

            // if(count($projects) > 0 )
            // {
            //     $collection->whereIn('project_id', $projects);
            // }
            // if(count($communities) > 0)
            // {
            //     $communityProjects = Project::whereIn('community_id', $communities)->active()->mainProject()->latest()->pluck('id');
            //     $collection->whereIn('project_id', $communityProjects);
            // }
            // if(count($developers) > 0)
            // {
            //     $collection->whereIn('project_id', Project::whereIn('developer_id', $developers)->active()->mainProject()->latest()->pluck('id'));
            // }

            $collection->where(function ($query) use ($projects, $developers, $communities) {

                if (!empty($projects)) {
                    $query->orWhereIn('project_id', $projects);
                }

                if (!empty($developers)) {
                    $query->orWhereIn('project_id', Project::whereIn('developer_id', $developers)->active()->mainProject()->approved()->latest()->pluck('id'));
                }

                if (!empty($communities)) {
                    $query->orWhereIn('project_id', Project::whereIn('community_id', $communities)->active()->mainProject()->approved()->latest()->pluck('id'));
                }
            });

            if (isset($request->isCommercial)) {
                // $commericalAcc =Accommodation::whereIn('type', ['Both', 'Commercial'])->active()->approved()->pluck('id')->toArray();
                // $collection->whereIn('accommodation_id', $commericalAcc);


                // $commericalAcc =Accommodation::whereIn('type', ['Both', 'Commercial'])->active()->approved()->pluck('id')->toArray();
                $collection->whereIn('used_for', ['Both', 'Commercial']);
            }
            if (isset($request->accommodation_id)) {
                $collection->where('accommodation_id', $request->accommodation_id);
            }

            // if (isset($request->community)) {
            //     $collection->where('community_id', $request->community);
            // }
            if (isset($request->bedrooms)) {
                $collection->where('bedrooms', $request->bedrooms);
            }

            if (isset($request->bathroom)) {
                $collection->where('bathrooms', $request->bathroom);
            }

            if (isset($request->furnishing)) {
                $collection->where('is_furniture', $request->furnishing);
            }
            if (isset($request->area)) {
                $collection->where('area', $request->area);
            }

            if (isset($request->minarea) || isset($request->maxarea)) {

                if (isset($request->minarea) && isset($request->maxarea)) {
                    $collection->whereBetween('area', [(int)$request->minarea, (int)$request->maxarea]);
                } else {
                    if (isset($request->minarea)) {
                        $collection->where('area', '>', (int)$request->minarea);
                    } elseif (isset($request->maxarea)) {
                        $collection->where('area', '<', (int)$request->maxarea);
                    }
                }
            }

            if (isset($request->exclusive)) {
                $collection->where('exclusive', $request->exclusive);
            }

            if (isset($request->minprice) || isset($request->maxprice)) {

                if (isset($request->minprice) && isset($request->maxprice)) {
                    $collection->whereBetween('price', [(int)$request->minprice, (int)$request->maxprice]);
                } else {
                    if (isset($request->minprice)) {
                        $collection->where('price', '>', (int)$request->minprice);
                    } elseif (isset($request->maxprice)) {
                        $collection->where('price', '<', (int)$request->maxprice);
                    }
                }
            }

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

            $properties = $collection->orderByRaw('ISNULL(propertyOrder)')->orderBy('propertyOrder', 'asc')->paginate(1000);

            $properties->appends(request()->query());

            
           
            //PropertyListResource::using(['currencyINR' => $currencyINR]);
            return $this->success('Properties', [
                'count' => $properties->count(),
                'properties' => new PropertyCollection($properties, $currencyINR),
                //'properties' => PropertyListResource::collection($properties)->response()->getData(true),

                // 'properties' =>$propertiesResource = $properties->map(function ($property) use ($currencyINR) {
                //     return new PropertyListResource($property, $currencyINR);
                // }),
                'amenities' =>  AmenitiesNameResource::collection($amenities),

            ], 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function properties(Request $request)
    {
        try {


            // Define your raw SQL query
            // $sql = "SELECT * FROM properties WHERE deleted_at is NULL";

            // // Count total records for pagination
            // $total = collect(DB::select("SELECT COUNT(*) as total FROM properties WHERE deleted_at is NULL"))->first()->total;

            // // Define pagination variables
            // $currentPage = LengthAwarePaginator::resolveCurrentPage();
            // $perPage = 15; // Number of items per page
            // $offset = ($currentPage * $perPage) - $perPage;

            // // Modify the query to get only the items for the current page
            // $paginatedItems = DB::select("$sql LIMIT $offset, $perPage");
            //  return $this->success('Properties',$paginatedItems, 200);

            $developers = [];
            $communities = [];
            $projects = [];
            if (isset($request->searchBy)) {
                foreach (json_decode($request->searchBy, true) as $search) {
                    $typeVaribale = explode("-", $search['type']);
                    if ($typeVaribale[0] == "developer") {
                        array_push($developers, $typeVaribale[1]);
                    } elseif ($typeVaribale[0] == "community") {
                        array_push($communities, $typeVaribale[1]);
                    } elseif ($typeVaribale[0] == "project") {
                        array_push($projects, $typeVaribale[1]);
                    }
                }
            }

            $collection = Property::with('completionStatus', 'accommodations', 'category')
                ->whereHas('project', function ($query) {
                    $query->whereNotNull('permit_number'); // Check if permit number is not null
                })->active();

                if(isset($request->currency)){
                    $currenyExist = Currency::where('name', $request->currency)->exists();
    
                    if($currenyExist){
                        $currency = Currency::where('name', $request->currency)->first()->value;
                    }
                    if (isset($request->minprice) || isset($request->maxprice)) {
                        $request->merge([
                            'minprice' => $request->minprice / $currency,
                            'maxprice' => $request->maxprice / $currency
                        ]);
    
                    }
                }
               

            if (isset($request->category)) {
                if ($request->category == 'rent') {
                    $categoryName = "Rent";
                    $collection->rent();
                } elseif ($request->category == 'buy') {
                    $collection->buy();
                    $categoryName = "Buy";
                }
            }

            if (isset($request->sortBy)) {
                if ($request->sortBy == 2) {
                    $collection->orderBy('price', 'asc');
                } elseif ($request->sortBy == 3) {
                    $collection->orderBy('price', 'desc');
                }
            } else {
                $collection->orderBy("id");
            }

            if (isset($request->completion_status_id) && $request->completion_status_id != "all") {
                $collection->where('completion_status_id', $request->completion_status_id);
            }
            if (isset($request->amenities)) {
                $amenity = explode(',', $request->amenities);

                $collection->whereHas('amenities', function ($query) use ($amenity) {
                    $query->whereIn('amenities.id', $amenity);
                });

                // $collection->whereIn('project_id',  Project::active()->mainProject()->whereHas('amenities', function ($query) use ($amenity) {
                //     $query->whereIn('amenities.id', $amenity);
                //     })->active()->pluck('id') )->latest()->pluck('id');

            }

            // if(count($projects) > 0 )
            // {
            //     $collection->whereIn('project_id', $projects);
            // }
            // if(count($communities) > 0)
            // {
            //     $communityProjects = Project::whereIn('community_id', $communities)->active()->mainProject()->latest()->pluck('id');
            //     $collection->whereIn('project_id', $communityProjects);
            // }
            // if(count($developers) > 0)
            // {
            //     $collection->whereIn('project_id', Project::whereIn('developer_id', $developers)->active()->mainProject()->latest()->pluck('id'));
            // }

            $collection->where(function ($query) use ($projects, $developers, $communities) {

                if (!empty($projects)) {
                    $query->orWhereIn('project_id', $projects);
                }

                if (!empty($developers)) {
                    $query->orWhereIn('project_id', DB::select("SELECT id as FROM projects WHERE 
                        projects.developer_id in  $developers AND 
                        projects.deleted_at is null AND 
                        projects.status = 'active' AND 
                        projects.is_approved = 'approved' AND
                        projects.is_parent_project IS true"));
                    //Project::whereIn('developer_id', $developers)->active()->mainProject()->approved()->latest()->pluck('id'));
                }

                if (!empty($communities)) {
                    $query->orWhereIn('project_id', DB::select("SELECT id as FROM projects WHERE 
                        projects.community_id in $communities AND 
                        projects.deleted_at is null AND 
                        projects.status = 'active' AND 
                        projects.is_approved = 'approved' AND
                        projects.is_parent_project IS true"));
                    //$query->orWhereIn('project_id', Project::whereIn('community_id', $communities)->active()->mainProject()->approved()->latest()->pluck('id'));
                }
            });

            if (isset($request->isCommercial)) {
                // $commericalAcc =Accommodation::whereIn('type', ['Both', 'Commercial'])->active()->approved()->pluck('id')->toArray();
                // $collection->whereIn('accommodation_id', $commericalAcc);

                $collection->whereIn('used_for', ['Both', 'Commercial']);
            }
            if (isset($request->accommodation_id)) {
                $collection->where('accommodation_id', $request->accommodation_id);
            }

            // if (isset($request->community)) {
            //     $collection->where('community_id', $request->community);
            // }
            if (isset($request->bedrooms)) {
                $collection->where('bedrooms', $request->bedrooms);
            }

            if (isset($request->bedrooms)) {
                $collection->where('bedrooms', $request->bedrooms);
            }

            if (isset($request->furnishing)) {
                $collection->where('is_furniture', $request->furnishing);
            }
            if (isset($request->area)) {
                $collection->where('area', $request->area);
            }

            if (isset($request->minarea) || isset($request->maxarea)) {
                if (isset($request->minarea)) {
                    $collection->where('area', '>', (int)$request->minarea);
                } elseif (isset($request->maxarea)) {
                    $collection->where('area', '<', (int)$request->maxarea);
                }
                if (isset($request->minarea) && isset($request->maxarea)) {
                    $collection->whereBetween('area', [(int)$request->minarea, (int)$request->maxarea]);
                }
            }

            if (isset($request->exclusive)) {
                $collection->where('exclusive', $request->exclusive);
            }

            if (isset($request->minprice) || isset($request->maxprice)) {
                if (isset($request->minprice)) {
                    $collection->where('price', '>', (int)$request->minprice);
                } elseif (isset($request->maxprice)) {
                    $collection->where('price', '<', (int)$request->maxprice);
                }
                if (isset($request->minprice) && isset($request->maxprice)) {
                    $collection->whereBetween('price', [(int)$request->minprice, (int)$request->maxprice]);
                }
            }

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
            $properties = $collection->orderByRaw('ISNULL(propertyOrder)')->orderBy('propertyOrder', 'asc')->paginate(1000);

            $properties->appends(request()->query());

            $properties = PropertyListResource::collection($properties)->response()->getData(true);

            return $this->success('Properties', $properties, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
}
