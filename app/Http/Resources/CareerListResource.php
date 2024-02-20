<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Models\{
    Community,
    WebsiteSetting
    
};
use DB;

class CareerListResource extends JsonResource
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
            'id'=>'career_'.$this->id,
            'careerId'=>$this->id,
            'position'=>$this->position,
            'slug'=>$this->slug,
            'type'=>$this->type,
            'location' => $this->location,
            'description' => $this->short_description
        ];
    }
}