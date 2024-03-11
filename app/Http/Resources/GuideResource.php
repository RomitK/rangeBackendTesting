<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class GuideResource extends JsonResource
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
            'id' => "guide_" . $this->id,
            'slug' => Str::slug($this->title),
            'source_id' => $this->crm_sub_source_id,
            'title' => $this->title,
            'description' => $this->description,
            'slider_image' => $this->slider_image,
            'feature_image' => $this->feature_image,
            'guide_file' => $this->guide_file,
        ];
    }
}
