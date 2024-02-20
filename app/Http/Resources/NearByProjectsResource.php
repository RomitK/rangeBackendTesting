<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class NearByProjectsResource extends JsonResource
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
            'id'=>"nearbyProject_".$this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'mainImage' => $this->banner_image,
            'accommodation'=>$this->accommodation ? $this->accommodation->name :''
        ];
    }
}