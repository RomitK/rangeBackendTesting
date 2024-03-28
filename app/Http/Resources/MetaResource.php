<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MetaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'title' => $this->meta_title ? $this->meta_title : 'Range International Property Investments',
            'meta_description' => $this->meta_description ? $this->meta_description : 'Range International Property Investments',
            'meta_keywords' => $this->meta_keywords ? $this->meta_keywords : "Range International Property Investment",
        ];
    }
}
