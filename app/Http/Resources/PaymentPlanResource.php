<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Models\{
    Property
};
use DB;

class PaymentPlanResource extends JsonResource
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
            'id'=>'paymentRow_'.$this->id,
            'name'=> $this->name, 
            'key'=> $this->key,
            'value'=> $this->value,
        ];
    }
}