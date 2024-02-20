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

class FaqListResource extends JsonResource
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
            'id'=>"faq_".$this->id,
            'question'=>$this->question,
            'answer'=>$this->long_answer->render()
        ];
    }
}