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
        $priceInINR = $this->currencyINR ? $this->price * $this->currencyINR : $this->price;

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
            'price_in_inr'=>$priceInINR,
            'mainImage' =>  $banner,
            'accommodation'=>$this->accommodationName
        ];
    }
}