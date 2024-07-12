<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleAgentResource extends JsonResource
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
            'id' => 'agent_' . $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'email' => $this->email,
            'contact' => $this->contact_number,
            'whatsapp' => $this->whatsapp_number,
            'image' => $this->image,
            'designation' => $this->designation,
            'languages' => $this->languages->pluck('name')
        ];
    }
}
