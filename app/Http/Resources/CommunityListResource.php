<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Models\{
    Community,
    WebsiteSetting
    
};
use DB;

class CommunityListResource extends JsonResource
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
            'id'=>'community_'.$this->id,
            // "community_id"=>$this->id,
            'name'=>$this->name,
            'slug'=>$this->slug,
            'mainImage' => $this->banner_image,
            'description' => $this->shortDescription,
            'communityOrder'=> $this->communityOrder
        ];
    }
}