<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Models\{
    Community,
    WebsiteSetting
    
};
use DB;

class CareerCounterResource extends JsonResource
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
            'id'=>'counter_'.$this->id,
            'key'=>$this->key,
            'value'=>$this->value
        ];
    }
}