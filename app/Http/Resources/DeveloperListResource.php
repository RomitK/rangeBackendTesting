<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Models\{
    Community,
    WebsiteSetting
    
};
use DB;

class DeveloperListResource extends JsonResource
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
            'id'=>'developer_'.$this->id,
            'name'=>$this->name,
            'slug'=>$this->slug,
            'logo'=>$this->logo_image,
            'developerOrder' => $this->developerOrder
        ];
    }
}