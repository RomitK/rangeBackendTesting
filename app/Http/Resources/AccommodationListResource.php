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

class AccommodationListResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'type'=> $this->type,
        ];
    }
}