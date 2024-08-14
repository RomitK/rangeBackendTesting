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

class PropertySimiliarListingR extends JsonResource
{
    protected $currencyINR;

    public function __construct($resource, $currencyINR)
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
        if( $this->bedrooms == 'Studio'){
            $bedrooms = "Studio";
        }else{
            if($this->bedrooms == 0 ){
                $bedrooms = $this->bedrooms. ' Bedrooms';
            }elseif($this->bedrooms > 1 ){
                $bedrooms = $this->bedrooms. ' Bedrooms';
            }else{
                $bedrooms = $this->bedrooms. ' Bedroom';
            }
        }
            
         if($this->bathrooms == 0 ){
            $bathrooms = '0 Bathroom';
        }elseif($this->bathrooms >1 ){
            $bathrooms = $this->bathrooms. ' Bathrooms';
        }else{
            $bathrooms = $this->bathrooms. ' Bathroom';
        }
        $priceInINR = $this->currencyINR ? $this->price * $this->currencyINR : $this->price;

        return [
            'id'=>'similar_'.$this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'price_in_inr'=>$priceInINR,
            'area' => $this->area,
            'unit_measure'=> 'sq ft',
            'accommodation' => $this->accommodationName,
            'property_banner' => $this->property_banner,
            'communityName' =>  $this->communityName,
            'bathrooms' => $this->bathrooms,
            'bedrooms' =>$this->bedrooms
        ];
    }
}