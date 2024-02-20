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

class PropertyProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $starting_price = 0; 
        $dateStr = $this->completion_date;
        $month = date("n", strtotime($dateStr));
        $yearQuarter = ceil($month / 3);
        
        return [
            'name' =>  $this->title,
            'slug' =>  $this->slug,
            'image' =>  $this->banner_image,
            'ExteriorGallery' =>$this->exteriorGallery,
            'handOver' => "Q".$yearQuarter." ".date("Y", strtotime($dateStr)),
            //'image' => $this->exteriorGallery[0]['path'],
            'description' =>  $this->short_description->render(),

        ];
    }
}