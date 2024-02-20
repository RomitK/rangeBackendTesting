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

class PropertyAgentResource extends JsonResource
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
            'name' =>  $this->name,
            // 'email' =>  $this->email
            
            'slug' => $this->slug,
            // 'whatsapp' =>  $this->whatsapp_number,
            // 'contact' =>  $this->contact_number,
            'whatsapp' =>  "+971506337953",
            'email' => "sales@range.ae",
            'contact' => '+971506337953',
            'image' =>  $this->image,
            'designation' => $this->designation,
        ];
    }
}