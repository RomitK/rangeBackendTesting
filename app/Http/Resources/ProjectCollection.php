<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectCollection extends ResourceCollection
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
     * @return array
     */
    public function toArray($request)
    {
        $pagination = $this->resource;

        return [
            'data' => ProjectListResource::collection($this->collection->map(function ($property) {
                return new ProjectListResource($property, $this->currencyINR);
            })),
            'links' => [
                'first' => $pagination->url(1),
                'last' => $pagination->url($pagination->lastPage()),
                'prev' => $pagination->previousPageUrl(),
                'next' => $pagination->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $pagination->currentPage(),
                'from' => $pagination->firstItem(),
                'last_page' => $pagination->lastPage(),
                'path' => $pagination->path(),
                'per_page' => $pagination->perPage(),
                'to' => $pagination->lastItem(),
                'total' => $pagination->total(),
            ],
        ];
    }
}
