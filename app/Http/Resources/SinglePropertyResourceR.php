<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Http\Resources\{
    SubProjectsResource,
    PaymentPlansResource,
    ProjectDeveloperResource,
    NearByProjectResource,
    PropertyDeveloperResource,
    PropertyCommunityResource,
    PropertyProjectResource,
    PropertyAmenitiesResource,
    PropertyAgentResource,
    PropertySimiliarListing,
    PropertySimiliarListingR
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

class SinglePropertyResourceR extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        if ($this->bedrooms == 'Studio') {
            $bedrooms = "Studio";
        } else {
            if ($this->bedrooms == 0) {
                $bedrooms = $this->bedrooms . ' Bedrooms';
            } elseif ($this->bedrooms > 1) {
                $bedrooms = $this->bedrooms . ' Bedrooms';
            } else {
                $bedrooms = $this->bedrooms . ' Bedroom';
            }
        }

        if ($this->bathrooms == 0) {
            $bathrooms = '0 Bathroom';
        } elseif ($this->bathrooms > 1) {
            $bathrooms = $this->bathrooms . ' Bathrooms';
        } else {
            $bathrooms = $this->bathrooms . ' Bathroom';
        }
        if ($this->property_source == "xml") {
            $gallery = $this->propertygallery->map(function ($img) {
                return [
                    'id' => "gallery_" . $img->id,
                    'path' => $img->galleryimage
                ];
            });
        } else {
            $gallery = $this->subImages;
        }

        $nearbyProperties = DB::select(DB::raw("select `properties`.`id`,properties.slug, properties.price, `properties`.`name`, projects.id as projectID, communities.id as communityID,  communities.name as communityName, `properties`.`area`,  `properties`.`property_banner`,  `properties`.`bedrooms`,  `properties`.`bathrooms`, accommodations.name as accommodationName from `properties` join projects on projects.id =properties.project_id join communities on communities.id = projects.community_id join accommodations on accommodations.id = properties.accommodation_id where `properties`.`slug` != '$this->slug' and  `properties`.`category_id` = '$this->category_id' and `properties`.`status` = 'active' and `properties`.`deleted_at` is null order by properties.created_at desc limit 0,12;"));


        return [
            'id' => $this->id,
            'name' => $this->name,
            'rental_period' => $this->rental_period,
            'permit_number' => $this->project->permit_number,
            'qr' => $this->project->qr,
            'reference_number' => $this->reference_number,
            'slug' => $this->slug,
            'youtube_video' => $this->youtube_video ? $this->youtube_video : 'https://www.youtube.com/watch?v=-6jlrq7idl8&list=PLiPk70af-7kf5A4vVxIWXr1yMaaoBTOb4',
            'address_latitude' => $this->address_latitude ? $this->address_latitude : '',
            'address_longitude' => $this->address_longitude ? $this->address_longitude : '',
            'default_latitude' =>  25.2048,
            'default_longitude' => 55.2708,
            'bathrooms' => $bathrooms,
            'bedrooms' => $bedrooms,
            'area' => $this->area,
            'unit_measure' => 'sq ft',
            'parking' => $this->parking_space,
            'accommodation' => $this->accommodations ?  $this->accommodations->name : '',
            'floorplans' => $this->subProject ? $this->subProject->floorPlan ? $this->subProject->floorPlan : [] : [],
            'price' => $this->price,
            'brochureLink' => url('/property/' . $this->slug . '/brochure'),
            'saleOfferLink' => url('/property/' . $this->slug . '/saleOffer'),
            'type' => $this->category ?  $this->category->name : '',
            'description' => $this->description->render(),
            'developer' => new PropertyDeveloperResource($this->project->developer),
            'community' => new PropertyCommunityResource($this->project->mainCommunity),
            'project' => new PropertyProjectResource($this->project),
            // 'amenities' => PropertyAmenitiesResource::collection($this->project->amenities),
            'amenities' => PropertyAmenitiesResource::collection($this->amenities),
            //'gallery'=>$gallery,
            'agent' => new PropertyAgentResource($this->agent),
            'category' => $this->category->name,
            //'similarProperties'=> PropertySimiliarListingR::collection($nearbyProperties)

        ];
    }
}
