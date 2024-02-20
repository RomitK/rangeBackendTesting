<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Models\{
    Community,
    WebsiteSetting
};
use App\Http\Resources\{
    AmenitiesResource,
    HighlightsResource,
    NearByCommunitiesResource,
    CommunityProperties
};
use Illuminate\Support\Arr;
use DB;

class HomeMapProjectsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $minBed = $this->subProjects->min('bedrooms');
        // $maxBed = $this->subProjects->max('bedrooms');
        // if($minBed != $maxBed){
        //     $bedroom = $minBed. "-".$maxBed;
        // }else{
        //     $bedroom = $minBed;
        // }
        
        if($this->subProjects->count() > 0){
            $bedrooms = $this->subProjects()->pluck('bedrooms')->toArray();
            if(in_array("Studio",$bedrooms)){
                $bedroom = "Studio";
                $array_without_studio = array_diff($bedrooms, array('Studio'));
                if(count($array_without_studio) > 0 ){
                    $array_without_studio = array_map('intval', $array_without_studio);
                    $minValue = min($array_without_studio);
                    $maxValue = max($array_without_studio);
                    
                    if($minValue != $maxValue){
                        $bedroom = "Studio & ". $minValue. "-".$maxValue. " BR";
                    }else{
                        $bedroom = "Studio & ".$maxValue. " BR";
                    }
                }else{
                    $bedroom = "Studio";
                }
            }else{
                $bedrooms = array_map('intval', $bedrooms);
                $minValue = min($bedrooms);
                $maxValue = max($bedrooms);
                if($minValue != $maxValue){
                    $bedroom = $minValue. "-".$maxValue. " BR";
                }else{
                    $bedroom = $maxValue. " BR";
                }
            }
            $area = $this->subProjects->min('area');
            $startingPrice = $this->subProjects->min('starting_price');
        }else{
            $area = 0;
            $startingPrice = 0;
            $bedroom = 0;
        }
        $dateStr = $this->completion_date;
        $month = date("n", strtotime($dateStr));
        $yearQuarter = ceil($month / 3);
        
        
            
        return [
            'id'=> "project_".$this->id,
            'title'=> $this->title,
            'slug'=>$this->slug,
            'lat' => (double)$this->address_latitude,
            'lng' => (double)$this->address_longitude,
            'handOver'=> "Q".$yearQuarter." ".date("Y", strtotime($dateStr)),
            'accommodationName' => $this->accommodation ? $this->accommodation->name: null,
            'completionStatusName' => $this->completionStatus ? $this->completionStatus->name: null,
            'starting_price' => $startingPrice,
            'bedrooms'=> $bedroom,
            'area'=> $area,
            'area_unit' => 'sq ft',
            'address'=> $this->address,
            'mainImage'=> $this->banner_image
        ];
    }
}