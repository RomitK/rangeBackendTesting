<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class NearByCommunitiesResource extends JsonResource
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
            'id'=>"nearbyCommunity_".$this->id,
            'name'=>$this->name,
            'slug'=>$this->slug,
            'mainImage' => $this->banner_image,
        ];
    }
}