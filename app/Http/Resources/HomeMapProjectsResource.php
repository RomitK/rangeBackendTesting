<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\{
    Community,
    WebsiteSetting
};
use App\Http\Resources\{
    AmenitiesResource,
    HighlightsResource,
    NearByCommunitiesResource,
    CommunityProperties
};
use Illuminate\Support\Arr;
use DB;

class HomeMapProjectsResource extends JsonResource
{

    protected $currencyINR;

    public function __construct($resource, $currencyINR = null)
    {

        parent::__construct($resource);
        $this->currencyINR = $currencyINR;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this['sub_projects'] = collect($this['sub_projects']);

        if ($this['has_sub_projects']) {

            $bedrooms = $this['sub_projects']->pluck('bedrooms')->toArray();

            if (in_array("Studio", $bedrooms)) {
                $bedroom = "Studio";
                $array_without_studio = array_diff($bedrooms, array('Studio'));
                if (count($array_without_studio) > 0) {
                    $array_without_studio = array_map('intval', $array_without_studio);
                    $minValue = min($array_without_studio);
                    $maxValue = max($array_without_studio);

                    if ($minValue != $maxValue) {
                        $bedroom = "Studio & " . $minValue . "-" . $maxValue . " BR";
                    } else {
                        $bedroom = "Studio & " . $maxValue . " BR";
                    }
                } else {
                    $bedroom = "Studio";
                }
            } else {
                $bedrooms = array_map('intval', $bedrooms);
                $minValue = min($bedrooms);
                $maxValue = max($bedrooms);
                if ($minValue != $maxValue) {
                    $bedroom = $minValue . "-" . $maxValue . " BR";
                } else {
                    $bedroom = $maxValue . " BR";
                }
            }
            $area = $this['sub_projects']->min('area');
            $startingPrice = $this['sub_projects']->min('starting_price');
        } else {
            $area = 0;
            $startingPrice = 0;
            $bedroom = 0;
        }
        $dateStr = $this['completion_date'];
        $month = date("n", strtotime($dateStr));
        $yearQuarter = ceil($month / 3);
        $priceInINR = $this->currencyINR ? $startingPrice * $this->currencyINR : $startingPrice;


        return [
            'id' => "project_" . $this['id'],
            'title' => $this['title'],
            'slug' => $this['slug'],
            'lat' => (float)$this['address_latitude'],
            'lng' => (float)$this['address_longitude'],
            'handOver' => "Q" . $yearQuarter . " " . date("Y", strtotime($dateStr)),
            'accommodationName' => $this['accommodation_name'],
            'completionStatusName' => $this['completion_statuses_name'],
            'starting_price' => $startingPrice,
            'starting_price_in_inr' => $priceInINR,
            'bedrooms' => $bedroom,
            'area' => $area,
            'area_unit' => 'sq ft',
            'address' => $this['address'],
            'mainImage' => $this['banner_image']
        ];
    }
}
