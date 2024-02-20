<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Models\{
    Community,
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
    DeveloperPropertiesResource
};
use Illuminate\Support\Arr;
use DB;

class ProjectOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => 'newProject_'.$this->id,
            'value' => $this->slug,
            'label' => $this->title,
        ];
    }
}