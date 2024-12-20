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

class ProjectListResource extends JsonResource
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
        // $minBed = $this->subProjects->min('bedrooms');
        // $maxBed = $this->subProjects->max('bedrooms');
        // if($minBed != $maxBed){
        //     if($maxBed === "Studio"){
        //         $bedroom = $maxBed. "-".$minBed;
        //     }else{
        //         $bedroom = $minBed. "-".$maxBed;
        //     }
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
                        $bedroom = "Studio & ". $minValue. "-".$maxValue. "BR";
                    }else{
                        $bedroom = "Studio & ".$maxValue. "BR";
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
            
            $minArea = $this->subProjects->min('area');
            $maxArea = $this->subProjects->max('area');
            if($minArea != $maxArea){
                $areaAvailable = $minArea. "-".$maxArea;
            }else{
                $areaAvailable = $minArea;
            }
            $starting_price =  $this->subProjects->min('starting_price');
        }else{
            $starting_price = 0;
            $bedroom = 0;
            $areaAvailable = 0;
        }
        
        $dateStr = $this->completion_date;
        $month = date("n", strtotime($dateStr));
        $yearQuarter = ceil($month / 3);
        
        
            
        return [
            'id'=>"project_".$this->id,
            'slug'=>$this->slug,
            'title'=>$this->title,
            'handOver'=> "Q".$yearQuarter." ".date("Y", strtotime($dateStr)),
            'lat'=> (double)$this->address_latitude, 
            'lng'=> (double)$this->address_longitude,
            'address'=> $this->address,
            'mainImage'=>$this->banner_image,
            'accommodationName'=> $this->accommodation ? $this->accommodation->name: null,
            'completionStatusName'=> $this->completionStatus ? $this->completionStatus->name: null,
            'starting_price'=> $starting_price,
            'starting_price_in_inr'=> $this->currencyINR ? $starting_price * $this->currencyINR : $starting_price,
            'area_unit' => 'sq ft',
            'bedrooms'=> $bedroom,
            'bathrooms'=>$this->bathrooms,
            'area' => $areaAvailable,
            'is_upcoming' => $this->upcoming_project,
        ];
    }
}