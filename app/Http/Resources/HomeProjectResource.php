<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Models\{
    Community,
    WebsiteSetting
};
use App\Http\Resources\{
    AmenitiesResource,
    HighlightsResource,
    NearByCommunitiesResource,
    CommunityProperties
};
use Illuminate\Support\Arr;
use DB;

class HomeProjectResource extends JsonResource
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
            'id' => 'project_'.$this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'mainImage' => $this->banner_image,
            'accommodation'=>$this->accommodation ? $this->accommodation->name: null,
            'mainImage'=> $this->banner_image,
            'projectOrder'=>$this->projectOrder
        ];
    }
}