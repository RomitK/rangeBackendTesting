<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Models\{
    Developer,
    Community,
    CompletionStatus,
    Accommodation,
    WebsiteSetting,
    Project
};
use App\Http\Resources\{
    AmenitiesResource,
    HighlightsResource,
    NearByCommunitiesResource,
    CommunityProperties
};
use Illuminate\Support\Arr;
use DB;

class CommunityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $accommodationId = null;
        $completionStatusId = null;
        $developerId = null;
        $projectId = null;


        if ($request->accommodation && $request->accommodation != 'All') {
            $accommodationId = Accommodation::where('name', $request->accommodation)->first()->id;
        }
        if ($request->completionStatus && $request->completionStatus != 'All') {
            $completionStatusId = CompletionStatus::where('name', $request->completionStatus)->first()->id;
        }

        if ($request->developer && $request->developer != 'All') {
            $developerId = Developer::where('name', $request->developer)->first()->id;
        }

        if ($request->project && $request->project != 'All') {
            $projectId = Project::where('title', $request->project)->first()->id;
        }


        $latitude = $this->address_latitude;
        $longitude = $this->address_longitude;
        $nearbyCommunities = array();
        $properties = array();
        if ($latitude && $longitude) {
            $nearbyCommunities = DB::select(DB::raw("select id, slug, ( 6367 * acos( cos( radians($latitude) ) * cos( radians(address_latitude ) ) * cos( radians( address_longitude ) - radians($longitude) ) + sin( radians($latitude) ) * sin( radians( address_latitude ) ) ) ) AS distance from `communities` 
                        where `communities`.`deleted_at` is null  and  
                        `communities`.`slug` <> '$this->slug' and
                        `communities`.`status` = 'active' and `communities`.`is_approved` = 'approved'
                        having `distance` < 10 order by `distance` asc limit 0,12;"));


            $nearbyCommunities = NearByCommunitiesResource::collection(Community::active()->whereIn('id', Arr::pluck($nearbyCommunities, 'id'))->get());
        }


        $propertiesQuery = "SELECT properties.id, properties.propertyOrder, properties.property_banner, accommodations.name as accommodation, properties.name, properties.slug, properties.price, properties.bedrooms, properties.area, properties.bathrooms, properties.category_id, properties.property_banner FROM `properties` 
                Join projects ON projects.id = properties.project_id
                Join accommodations ON accommodations.id = properties.accommodation_id
                Where properties.deleted_at is null AND properties.status = 'active' AND `properties`.`is_approved` = 'approved' AND projects.community_id = '$this->id' AND 
                projects.deleted_at is null AND projects.status = 'active' AND projects.permit_number is not null AND projects.is_parent_project IS true AND  `projects`.`is_approved` = 'approved'";


        if ($accommodationId) {
            $propertiesQuery .= " AND properties.accommodation_id = $accommodationId";
        }
        if ($projectId) {
            $propertiesQuery .= " AND properties.project_id = $projectId";
        }

        if ($developerId) {
            $propertiesQuery .= " AND projects.developer_id = $developerId";
        }

        if ($completionStatusId) {
            $propertiesQuery .= " AND projects.completion_status_id = $completionStatusId";
        }


        //For category_id 8
        $properties = DB::select(DB::raw($propertiesQuery));
        $saleProperties = DB::select(DB::raw($propertiesQuery . " AND properties.category_id = 8 order by ISNULL(propertyOrder), `propertyOrder` asc"));

        // For category_id 9
        $rentProperties = DB::select(DB::raw($propertiesQuery . " AND properties.category_id = 9 order by ISNULL(propertyOrder), `propertyOrder` asc"));

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
        if ($this->meta_keyword) {
            $meta_keyword = $this->meta_keywords;
        } else {
            $meta_keyword = WebsiteSetting::getSetting('keywords') ? WebsiteSetting::getSetting('keywords') : '';
        }

        return [
            'id' => 'community-' . $this->id,
            'default_longitude' => '55.296249',
            'default_latitude' =>    '25.276987',
            'name' => $this->name,
            'slug' => $this->slug,
            'address_latitude' => $this->address_latitude,
            'address_longitude' => $this->address_longitude,
            'amenities' => AmenitiesResource::collection($this->amenities),
            'highlights' => HighlightsResource::collection($this->highlights),
            'imageGallery' => $this->imageGallery,
            'meta_keyword' => $meta_keyword,
            'meta_title' => $meta_title,
            'meta_description' => $meta_description,
            'nearbyCommunities' => $nearbyCommunities,
            'properties' => CommunityProperties::collection($properties),
            'saleProperties' => CommunityProperties::collection($saleProperties),
            'rentProperties' => CommunityProperties::collection($rentProperties),

            'longDescription' => $this->description->render()

        ];
    }
}
