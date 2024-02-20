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
    DeveloperPropertiesResource,
    ProjectOptionResource
};
use Illuminate\Support\Arr;
use DB;

class SingleCareerResource extends JsonResource
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
            'position' => $this->position,
            'slug' => $this->slug,
            'type' => $this->type,
            'location' => $this->location,
            'description' => $this->description->render(),
            'responsibilities' => (strlen(trim(strip_tags(str_replace('&#13;','',trim($this->key_responsibilities))))) > 0)? $this->key_responsibilities->render() : '',
            'requirements' => (strlen(trim(strip_tags(str_replace('&#13;','',trim($this->requirements))))) > 0)? $this->requirements->render() : '',
            'benfits' => (strlen(trim(strip_tags(str_replace('&#13;','',trim($this->benefits))))) > 0)? $this->benefits->render() : '',
            'assistance' => (strlen(trim(strip_tags(str_replace('&#13;','',trim($this->assistance))))) > 0)? $this->assistance->render() : '',
        ];
    }
}