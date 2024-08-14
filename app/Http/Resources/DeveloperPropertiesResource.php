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
    DeveloperProjectsResource
};
use Illuminate\Support\Arr;
use DB;

class DeveloperPropertiesResource extends JsonResource
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

        return [
            'id'=>"property_".$this->id,
            'name'=>$this->name,
            'slug'=>$this->slug,
            'property_banner'=> $this->property_banner,
            'accommodation' => $this->accommodation,
            'community'=> $this->communityName,
            'bedrooms'=>$this->bedrooms,
            'bathrooms'=>$this->bathrooms,
            'area'=>$this->area,
            'unit_measure'=>'sq ft',
            'price'=>$this->price,
            'price_in_inr'=>$priceInINR,
        ];
    }
}