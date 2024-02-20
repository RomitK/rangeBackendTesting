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

class PropertySimiliarListing extends JsonResource
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
            'id'=>'similar_'.$this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'area' => $this->area,
            'unit_measure'=> 'sq ft',
            'accommodation' => $this->accommodations?  $this->accommodations->name:'',
            'property_banner' => $this->property_banner,
            'communityName' =>  $this->communities->name,
            'bathrooms' => $this->bathrooms,
            'bedrooms' => $this->bedrooms
        ];
    }
}