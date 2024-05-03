<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Http\Resources\{
    SubProjectsResource,
    PaymentPlansResource,
    ProjectDeveloperResource,
    NearByProjectResource,
    NearByProjectsResource,
    ProjectPropertiesResource,
    AmenitiesResource
};
use App\Models\{
    Project,
    WebsiteSetting,
    Accommodation,
    Category,
    Community,
    Property,
    CompletionStatus
};
use DB;
use Illuminate\Support\Arr;

class SingleProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $starting_price = 0;
        $dateStr = $this->completion_date;
        $month = date("n", strtotime($dateStr));
        $yearQuarter = ceil($month / 3);
        $bedrooms = 0;
        $bedroom = 0;
        $areaAvailable = 0;

        if (count($this->subProjects) > 0) {
            $bedrooms = $this->subProjects()->pluck('bedrooms')->toArray();

            if (in_array("Studio", $bedrooms)) {
                $bedroom = "Studio";
                $array_without_studio = array_diff($bedrooms, array('Studio'));
                if (count($array_without_studio) > 0) {
                    $array_without_studio = array_map('intval', $array_without_studio);
                    $minValue = min($array_without_studio);
                    $maxValue = max($array_without_studio);

                    if ($minValue != $maxValue) {
                        $bedroom = "Studio & " . $minValue . "-" . $maxValue . " BR";
                    } else {
                        $bedroom = "Studio & " . $maxValue . " BR";
                    }
                } else {
                    $bedroom = "Studio";
                }
            } else {
                $bedrooms = array_map('intval', $bedrooms);
                $minValue = min($bedrooms);
                $maxValue = max($bedrooms);
                if ($minValue != $maxValue) {
                    $bedroom = $minValue . "-" . $maxValue . " BR";
                } else {
                    $bedroom = $maxValue . " BR";
                }
            }

            $minArea = $this->subProjects->min('area');
            $maxArea = $this->subProjects->max('area');

            if ($minArea != $maxArea) {
                $areaAvailable = $minArea . "-" . $maxArea;
            } else {
                $areaAvailable = $minArea;
            }
        }


        // $minBed = $this->subProjects->min('bedrooms');
        // $maxBed = $this->subProjects->max('bedrooms');
        // if($minBed != $maxBed){
        //     if($maxBed == "Studio"){
        //         $bedroom = $maxBed. "-".$minBed;
        //     }else{
        //         $bedroom = $minBed. "-".$maxBed;
        //     }

        // }else{
        //     $bedroom = $minBed;
        // }


        //$areaUnit = 'sq ft';

        if (count($this->subProjects) > 0) {
            $area =  $this->subProjects->where('area', $this->subProjects->min('area'))->first()->area;
            $starting_price = $this->subProjects->where('starting_price', $this->subProjects->min('starting_price'))->first()->starting_price;
        }

        if (count($this->subProjects) > 0) {
            $minPrice = $this->subProjects->where('starting_price', $this->subProjects->min('starting_price'))->first()->starting_price;
            $maxPrice = $this->subProjects->where('starting_price', $this->subProjects->max('starting_price'))->first()->starting_price;
        } else {
            $minPrice = 0;
            $maxPrice = 0;
        }
        if ($this->meta_title) {
            $meta_title = $this->meta_title;
        } else {
            $meta_title = WebsiteSetting::getSetting('website_name') ? WebsiteSetting::getSetting('website_name') : '';
        }
        if ($this->meta_description) {
            $meta_description = $this->meta_description;
        } else {
            $meta_description = WebsiteSetting::getSetting('description') ? WebsiteSetting::getSetting('description') : '';
        }
        if ($this->meta_keywords) {
            $meta_keywords = $this->meta_keywords;
        } else {
            $meta_keywords = WebsiteSetting::getSetting('keywords') ? WebsiteSetting::getSetting('keywords') : '';
        }
        $latitude = $this->address_latitude;
        $longitude = $this->address_longitude;
        $nearbyProjects = array();
        if ($latitude && $longitude) {
            $nearbyProjects = DB::select(DB::raw("select id, slug, ( 6367 * acos( cos( radians($latitude) ) * cos( radians(address_latitude ) ) * cos( radians( address_longitude ) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians( address_latitude ) ) ) ) AS distance from `projects` 
                where `projects`.`deleted_at` is null  and  
                `projects`.`slug` <> '$this->slug' and
                `projects`.`status` = 'active' and `projects`.`is_approved` = 'approved'
                having `distance` < 10 order by `distance` asc limit 0,12;"));
        }
        if ($this->permit_number) {
            $rentProperties =  DB::select(
                "
                                SELECT properties.id, properties.name, properties.status,properties.category_id, properties.price, properties.is_approved,properties.slug, properties.property_source, properties.bedrooms, properties.area, properties.property_banner, accommodations.name as accommodationName, properties.bedrooms, properties.bathrooms
                                FROM properties
                                JOIN accommodations ON accommodations.id = properties.accommodation_id
                                WHERE properties.project_id = ?
                                  AND properties.category_id = ?
                                  AND properties.status = ?
                                  AND properties.is_approved = ?
                                  AND properties.deleted_at is null
                                ORDER BY properties.created_at DESC
                                LIMIT 8",
                [$this->id, config('constants.rentId'), 'active', 'approved']
            );

            $buyProperties =  DB::select(
                "
                                SELECT properties.id, properties.name, properties.status,properties.category_id,properties.price, properties.is_approved, properties.slug, properties.property_source, properties.bedrooms, properties.area, properties.property_banner, accommodations.name as accommodationName, properties.bedrooms, properties.bathrooms
                                FROM properties
                                JOIN accommodations ON accommodations.id = properties.accommodation_id
                                WHERE properties.project_id = ?
                                  AND properties.category_id = ?
                                  AND properties.status = ?
                                  AND properties.is_approved = ?
                                  AND properties.deleted_at is null
                                ORDER BY properties.created_at DESC
                                LIMIT 8",
                [$this->id, config('constants.buyId'), 'active', 'approved']
            );
        } else {
            $rentProperties = [];
            $buyProperties = [];
        }


        $exteriorGallery = $this->exteriorGallery;

        $count = count($exteriorGallery);

        // Duplicate elements until the count reaches at least 3
        while ($count < 3) {
            // Choose an element to duplicate (you can choose any index or the last one as an example)
            $elementToDuplicate = end($exteriorGallery);

            // Duplicate the element
            $exteriorGallery[] = $elementToDuplicate;

            // Update the count
            $count++;
        }


        return [
            'id' => 'project-' . $this->id,
            'permit_number' => $this->permit_number,
            'title' => $this->title,
            'meta_title' => $meta_title,
            'meta_keywords' => $meta_keywords,
            'meta_description' => $meta_description,
            'sub_title_1' => $this->sub_title,
            'sub_title_2' => $this->sub_title_1,
            'slug' => $this->slug,
            'address_latitude' => $this->address_latitude,
            'address_longitude' => $this->address_longitude,
            'price' => $starting_price,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'communityName' => $this->mainCommunity ? $this->mainCommunity->name : '',
            'availableUnits' =>  $bedroom,
            'handOver' => "Q" . $yearQuarter . " " . date("Y", strtotime($dateStr)),
            'areaAvailable' => $areaAvailable,
            'areaUnit' => 'sq ft',
            'amenities' => AmenitiesResource::collection($this->amenities),
            'payment' => PaymentPlansResource::collection($this->mPaymentPlans),
            // 'types'=> SubProjectsResource::collection($this->subProjects()->active()->approved()->get()),
            'types' => SubProjectsResource::collection($this->subProjects),
            'developer' => new ProjectDeveloperResource($this->developer),
            //'rentProperties'=>ProjectPropertiesResource::collection( Property::with('accommodations')->where('project_id', $this->id)->where('category_id', config('constants.rentId'))->active()->approved()->latest()->limit(8)->get()),
            //'buyProperties'=>ProjectPropertiesResource::collection( Property::with('accommodations')->where('project_id', $this->id)->where('category_id', config('constants.buyId'))->active()->approved()->latest()->limit(8)->get()),


            'rentProperties' => ProjectPropertiesResource::collection($rentProperties),
            'buyProperties' => ProjectPropertiesResource::collection($buyProperties),

            'interiorGallery' => $this->interiorGallery,
            'exteriorGallery' => $exteriorGallery,
            'shortDescription' => $this->short_description->render(),
            'longDescription' => $this->long_description->render(),
            //'hightlightDescription' => $this->features_description->render(),
            'brochureLink' => url('/project/' . $this->slug . '/brochure')

        ];
    }
}
