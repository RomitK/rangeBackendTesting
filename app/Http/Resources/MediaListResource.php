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

class MediaListResource extends JsonResource
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
            'id'=>'media_'.$this->id,
            'title'=>$this->title,
            'slug'=>$this->slug,
            'type'=>$this->article_type,
            'image'=>$this->article_banner,
            'short_content'=> $this->short_content,
            'date'=> $this->formattedPublishAt
        ];
    }
}