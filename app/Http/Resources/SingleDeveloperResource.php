<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Models\{
    CompletionStatus,
    Community,
    Accommodation,
    WebsiteSetting,
    Project
};
use App\Http\Resources\{
    AmenitiesResource,
    HighlightsResource,
    NearByCommunitiesResource,
    CommunityProperties,
    DeveloperCommunitiesResource,
    DeveloperProjectsResource,
    DeveloperPropertiesResource,
    ProjectOptionResource
};
use Illuminate\Support\Arr;
use DB;

class SingleDeveloperResource extends JsonResource
{

    protected $currencyINR;

    public function __construct($resource, $currencyINR = null)
    {
        parent::__construct($resource);
        $this->currencyINR = $currencyINR;
    }

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
        $communityId = null;
        $available = config('constants.available');

        if ($request->accommodation && $request->accommodation != 'All') {
            $accommodationId = Accommodation::where('name', $request->accommodation)->first()->id;
        }
        if ($request->completionStatus && $request->completionStatus != 'All') {

            $completionStatusId = CompletionStatus::where('name', $request->completionStatus)->first()->id;
        }

        if ($request->community && $request->community != 'All') {
            $communityId = Community::where('name', $request->community)->first()->id;
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

        $propertiesQuery = "SELECT properties.id, properties.property_banner, 
                communities.name as communityName,  
                accommodations.name as accommodation, 
                properties.name, 
                properties.slug, 
                properties.price, 
                properties.bedrooms,
                properties.area, properties.bathrooms 
                FROM `properties` 
                Join projects ON projects.id = properties.project_id
                Join developers ON developers.id = projects.developer_id
                Join accommodations ON accommodations.id = properties.accommodation_id
                Join communities ON communities.id = projects.community_id
                Where properties.deleted_at is null AND properties.website_status = '$available' AND properties.is_valid = 1 AND
                
                projects.deleted_at is null AND 
                projects.website_status = '$available' AND 
                projects.is_parent_project IS true AND
                developers.id = $this->id";

        if ($accommodationId) {
            $propertiesQuery .= " AND properties.accommodation_id = $accommodationId";
        }
        if ($communityId) {
            $propertiesQuery .= " AND properties.community_id = $communityId";
        }


        //For category_id 8
        $properties = DB::select(DB::raw($propertiesQuery . "  limit 0,12"));



        $saleProperties = DB::select(DB::raw($propertiesQuery . " AND properties.category_id = 8 limit 0,12"));

        // For category_id 9
        $rentProperties = DB::select(DB::raw($propertiesQuery . " AND properties.category_id = 9 limit 0,12"));

        // $properties = DB::select(DB::raw("SELECT properties.id, properties.property_banner, 
        //         communities.name as communityName,  
        //         accommodations.name as accommodation, 
        //         properties.name, 
        //         properties.slug, 
        //         properties.price, 
        //         properties.bedrooms,
        //         properties.area, properties.bathrooms 
        //         FROM `properties` 
        //         Join projects ON projects.id = properties.project_id
        //         Join developers ON developers.id = projects.developer_id
        //         Join accommodations ON accommodations.id = properties.accommodation_id
        //         Join communities ON communities.id = projects.community_id
        //         Where properties.deleted_at is null AND properties.status = 'active' AND
        //         projects.deleted_at is null AND 
        //         projects.status = 'active' AND 
        //         projects.is_approved = 'approved' AND
        //         projects.is_parent_project IS true AND
        //         developers.id = $this->id
        //         limit 0,12
        //         ;"));



        $developerProjects = Project::mainProject()->where('developer_id', $this->id)->where('website_status', config('constants.available'));
        if ($accommodationId) {
            $developerProjects = $developerProjects->where('accommodation_id', $accommodationId);
        }
        if ($completionStatusId) {
            $developerProjects = $developerProjects->where('completion_status_id', $completionStatusId);
        }
        if ($communityId) {
            $developerProjects = $developerProjects->where('community_id', $communityId);
        }


        $exteriorGallery = $this->gallery;

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


        $projectOptions = clone $developerProjects;


        return [
            'id' => 'developer-' . $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'imageGallery' => $exteriorGallery,
            'longDescription' => $this->long_description->render(),
            'shortDescription' => $this->short_description->render(),
            'newProjects' => ProjectOptionResource::collection($projectOptions->OrderBy('title', 'asc')->get()),
            
            // 'projects' => DeveloperProjectsResource::collection($developerProjects->orderByRaw('ISNULL(projectOrder)')->orderBy('projectOrder', 'asc')->get()),
            // 'developrMapProjects' =>  DeveloperProjectsResource::collection($developerProjects->orderByRaw('ISNULL(projectOrder)')->orderBy('projectOrder', 'asc')->get()),
            

            'projects' => new DeveloperProjectsCollectionResource($developerProjects->orderByRaw('ISNULL(projectOrder)')->orderBy('projectOrder', 'asc')->get(),  $this->currencyINR),
            'developrMapProjects' =>  new DeveloperProjectsCollectionResource($developerProjects->orderByRaw('ISNULL(projectOrder)')->orderBy('projectOrder', 'asc')->get(),  $this->currencyINR),


            
            
            'communities' => DeveloperCommunitiesResource::collection($this->communityDevelopers()->active()->approved()->orderByRaw('ISNULL(communityOrder)')->orderBy('communityOrder', 'asc')->get()),
            // 'properties'=> DeveloperPropertiesResource::collection($properties),
           // 'saleProperties' => DeveloperPropertiesResource::collection($saleProperties),
            'saleProperties' => new DeveloperPropertiesCollectionResource($saleProperties, $this->currencyINR),
            
            //'rentProperties' => DeveloperPropertiesResource::collection($rentProperties),
            'rentProperties' => new DeveloperPropertiesCollectionResource($rentProperties, $this->currencyINR),
            'meta_keyword' => $meta_keywords,
            'meta_title' => $meta_title,
            'meta_description' => $meta_description,
        ];
    }
}
