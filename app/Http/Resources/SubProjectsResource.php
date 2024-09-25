<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Models\{
    Property
};
use DB;

class SubProjectsResource extends JsonResource
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
        $priceInINR = $this->currencyINR 
        ? (is_int($this->starting_price) ? $this->currencyPrice : $this->starting_price * $this->currencyINR) 
        : $this->starting_price;

        if (Property::where('sub_project_id', $this->id)->where('website_status', config('constants.available'))->where('is_valid', 1)->exists()) {
            $property =  Property::where('sub_project_id', $this->id)->where('website_status', config('constants.available'))->where('is_valid', 1)->first()->slug;
        } else {
            $property = null;
        }
        if ($this->accommodation_id  == 3) {
            if ($this->area && $this->builtup_area) {

                $area = "PLOT:" . $this->area . "-" . "BUA:" . $this->builtup_area;
            } else {
                $area = $this->area;
            }
        } else {
            $area = $this->area;
        }

        return [
            'id' => "type_" . $this->id,
            'name' => $this->title,
            'bedrooms' => $this->bedrooms,
            'startingPrice' => $this->starting_price,
            'startingPriceInINR' => $priceInINR,
            'area' =>  $area,
            'areaUnit' => $this->area_unit ? $this->area_unit : 'Sq.Ft',
            'accommodation' => $this->accommodation ? $this->accommodation->name : '',
            'floorPlan' => $this->floorPlan,
            'property' => $property,
        ];
    }
}
