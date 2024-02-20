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

class PropertyCommunityResource extends JsonResource
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
            'address_latitude' => $this->address_latitude?  $this->address_latitude : 25.2048,
            'address_longitude' => $this->address_longitude? $this->address_longitude :55.2708,
            'name' =>  $this->name,
            'slug' =>  $this->slug,
            'gallery' =>  $this->imageGallery,
            'description' =>  $this->shortDescription,

        ];
    }
}