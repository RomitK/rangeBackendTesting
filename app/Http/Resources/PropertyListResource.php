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

class PropertyListResource extends JsonResource
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
            'id'=>"property_".$this->id,
            'slug'=>$this->slug,
            'name'=>$this->name,
            'lat'=> (double)$this->address_latitude, 
            'lng'=> (double)$this->address_longitude,
            'address'=> $this->address,
            'property_banner'=>$this->property_banner,
            'accommodationName'=> $this->accommodations ? $this->accommodations->name: null,
            'completionStatusName'=> $this->completionStatus ? $this->completionStatus->name: null,
            'categoryName'=> $this->category ? $this->category->name : null,
            'price'=> $this->price,
            'area_unit' => 'sq ft',
            'bedrooms'=> $this->bedrooms,
            'bathrooms'=>$this->bathrooms,
            'area' => $this->area,
            'propertyOrder'=>$this->propertyOrder
            
        ];
    }
}