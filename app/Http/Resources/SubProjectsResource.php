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
       // Ensure starting_price and currencyINR are treated as numbers
$startingPrice = floatval($this->starting_price); // Convert to float
$currencyINR = floatval($this->currencyINR); // Convert to float

if ($this->currencyINR) {
    $priceInINR = is_int($startingPrice) 
        ? $this->currencyPrice 
        : $startingPrice * $currencyINR;
} else {
    $priceInINR = $startingPrice; // Use starting_price directly if currencyINR is not set
}


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
