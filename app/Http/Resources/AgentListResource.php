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
            'id' => 'agent_' . $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            // 'email'=>$this->email,
            // 'contact' => $this->contact_number,
            // 'whatsapp'=>$this->whatsapp_number,

            'whatsapp' =>  $this->is_display_details == 0 ? "+971507672643" : $this->whatsapp_number,
            'email' => $this->is_display_details == 0 ? "sales@range.ae" : $this->email,
            'contact' => $this->is_display_details == 0 ? '+971506337953' : $this->contact_number,

            'image' => $this->image,
            'designation' => $this->designation,
            'languages' => $this->languages->pluck('name')
        ];
    }
}
