<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Models\{
    Property
};
use DB;

class ProjectDeveloperResource extends JsonResource
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
            'name'=> $this->name, 
            'slug'=> $this->slug,
            'logo'=> $this->logo,
            'description' =>  $this->short_description->render(),
        ];
    }
}