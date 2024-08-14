<?php

namespace App\Http\Resources;



use Illuminate\Http\Resources\Json\ResourceCollection;

class DeveloperPropertiesCollectionResource extends ResourceCollection


{
    protected $currencyINR;

    public function __construct($resource, $currencyINR = null)
    {
        parent::__construct($resource);
        $this->currencyINR = $currencyINR;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($property) {
            return new DeveloperPropertiesResource($property, $this->currencyINR);
        })->all(); // Convert the mapped collection to an array
    }
}
