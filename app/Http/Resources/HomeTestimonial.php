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

class HomeTestimonial extends JsonResource
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
            'id' => 'testimonal_'.$this->id,
            'clientName' => $this->client_name,
            'feedback' => $this->feedback,
            'star'=> $this->rating
        ];
    }
}