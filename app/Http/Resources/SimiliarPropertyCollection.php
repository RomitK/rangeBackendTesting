<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SimiliarPropertyCollection extends ResourceCollection
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
        // Pass the $currencyINR to the resource
        return PropertySimiliarListingR::collection(
            $this->collection->map(function ($property) {
                return new PropertySimiliarListingR($property, $this->currencyINR);
            })
        )->toArray($request); // Ensure the resources are converted to an array

        
        
        // PropertySimiliarListingR::collection($this->resource)->additional(['currencyINR' => $this->currencyINR]);
    }
}