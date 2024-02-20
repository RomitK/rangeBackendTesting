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

class AgentListResource extends JsonResource
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
            'id'=>'agent_'.$this->id,
            'name'=>$this->name,
            'slug'=>$this->slug,
            // 'email'=>$this->email,
            // 'contact' => $this->contact_number,
            // 'whatsapp'=>$this->whatsapp_number,
            
            'whatsapp' =>  "+971507672643",
            'email' => "sales@range.ae",
            'contact' => '+971506337953',
            
            'image'=>$this->image,
            'designation'=>$this->designation,
            'languages'=>$this->languages->pluck('name')
        ];
    }
}