<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Http\Resources\{
    SubProjectsResource,
    PaymentPlansResource,
    ProjectDeveloperResource,
    NearByProjectResource
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

class ProjectPropertiesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->property_source == "crm"){
            //$banner = $this->mainImage;
            $banner = $this->property_banner;
        }else{
            $banner = $this->property_banner;
        }
        return [
            'id'=>"rentProperty_".$this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'bedrooms'=>$this->bedrooms,
            'area'=>$this->area,
            'unit_measure'=>'sq ft',
            'bathrooms'=>$this->bathrooms,
            'price'=>$this->price,
            'mainImage' =>  $banner,
            'accommodation'=>$this->accommodationName
        ];
    }
}