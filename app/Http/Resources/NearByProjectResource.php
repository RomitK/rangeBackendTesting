<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class NearByProjectResource extends JsonResource
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
            'mainImage' => $this->mainImage,
            'accommodation'=>$this->accommodation ? $this->accommodation->name :''
        ];
    }
}