<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Community,
    WebsiteSetting
};
use App\Http\Resources\{
    CommunityResource,
    CommunityListResource
};
use DB;

class CommunityController extends Controller
{
    public function getHomeCommunities()
    {
        try {
            $communities = Community::select('id', 'name', 'slug')->approved()->active()->home()->limit(8)->get();
            return $this->success('Home Communities', $communities, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage(), $exception->getCode());
        }
    }

    public function commnunityOptions()
    {
        try {
            $developers = Community::whereHas('projects')->active()->approved()->orderBy('name')->get()->map(function ($developer) {
                return [
                    'value' => $developer->id,
                    'label' => $developer->name
                ];
            });
            $developers->prepend([
                'value' => '',
                'label' => 'All'
            ]);
            return $this->success('Community Options', $developers, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function index(Request $request)
    {
        try {
            // $collection = Community::active()->approved();
            $collection = Community::active()->approved();
            if (isset($request->completion_status_id) && $request->completion_status_id != null) {
                $collection->whereHas('projects', function ($q) use ($request) {
                    $q->where('completion_status_id', $request->completion_status_id);
                });
            }

            if (isset($request->developer_id) && $request->developer_id != null) {
                $collection->whereHas('developers', function ($q) use ($request) {
                    $q->where('developer_id', $request->developer_id);
                });
            }

            if (isset($request->project_id) && $request->project_id != null) {
                $collection->whereHas('projects', function ($q) use ($request) {
                    $q->where('id', $request->project_id);
                });
            }

            if (isset($request->accommodation_id) && $request->accommodation_id != null) {
                $collection->whereHas('projects', function ($q) use ($request) {
                    $q->where('accommodation_id', $request->accommodation_id);
                });
            }

            $communities =  $collection->select('id', 'name', 'slug', 'banner_image', 'shortDescription', 'communityOrder')->orderByRaw('ISNULL(communityOrder)')->orderBy('communityOrder', 'asc')->paginate(200);

            $communities = CommunityListResource::collection($communities)->response()->getData(true);
            return $this->success('All Communities', $communities, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function singleCommunityMeta($slug)
    {
        try {
            if (Community::where('slug', $slug)->exists()) {
                $community = DB::table('communities')->select('meta_title', 'name', 'meta_description', 'meta_keywords')->where('slug', $slug)->first();
                $singleCommunity = (object)[];


                if ($community->meta_title) {
                    $singleCommunity->meta_title = $community->meta_title;
                } else {
                    $singleCommunity->meta_title = WebsiteSetting::getSetting('website_name') ? WebsiteSetting::getSetting('website_name') : '';
                }
                if ($community->meta_description) {
                    $singleCommunity->meta_description = $community->meta_description;
                } else {
                    $singleCommunity->meta_description = WebsiteSetting::getSetting('description') ? WebsiteSetting::getSetting('description') : '';
                }

                if ($community->meta_keywords) {
                    $singleCommunity->meta_keyword = $community->meta_keywords;
                } else {
                    $singleCommunity->meta_keyword = WebsiteSetting::getSetting('keywords') ? WebsiteSetting::getSetting('keywords') : '';
                }

                return $this->success('Single Community Meta', $singleCommunity, 200);
            } else {
                return $this->success('Single Community Meta', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function singleCommunity($slug)
    {
        try {
            if (Community::where('slug', $slug)->exists()) {
                $community = Community::where('slug', $slug)->first();

                $longitude = $community->address_latitude;
                $latitude = $community->address_longitude;
                $nearbyCommunities = Community::select('id', 'name', 'slug', 'address_latitude', 'address_longitude')->active()->approved();
                $nearbyproperty = $nearbyCommunities->select("*", DB::raw("6371 * acos(cos(radians(" . $latitude . "))* cos(radians(address_latitude)) * cos(radians(address_longitude) - radians(" . $longitude . ")) + sin(radians(" . $latitude . ")) * sin(radians(address_latitude))) AS distance"));

                $nearbyCommunities = $nearbyCommunities->having('distance', '<', 50000);

                $nearbyCommunities = $nearbyCommunities->orderBy('distance', 'asc');

                $nearbyCommunities = $nearbyCommunities->where('slug', '!=', $slug)->take(12)->get()->map(function ($community) {
                    return [
                        'id' => "nearbyCommunity_" . $community->id,
                        'name' => $community->name,
                        'slug' => $community->slug,
                        'mainImage' => $community->mainImage,
                    ];
                });

                $singleCommunity = (object)[
                    'default_longitude' => '55.296249',
                    'default_latitude' =>    '25.276987'
                ];

                $singleCommunity->id = $community->id;
                $singleCommunity->name = $community->name;
                $singleCommunity->slug = $community->slug;
                $singleCommunity->address_latitude = $community->address_latitude;
                $singleCommunity->address_longitude = $community->address_longitude;
                //$singleCommunity->location_iframe = $community->location_iframe;
                $singleCommunity->imageGallery = $community->imageGallery;
                $singleCommunity->longDescription = $community->description->render();
                $singleCommunity->amenities = $community->amenities->map(function ($amenity) {
                    return [
                        'id' => "amenity_" . $amenity->id,
                        'name' => $amenity->name,
                        'image' => $amenity->image
                    ];
                });
                $singleCommunity->highlights = $community->highlights->map(function ($highlight) {
                    return [
                        'id' => "highlight_" . $highlight->id,
                        'name' => $highlight->name,
                        'image' => $highlight->image
                    ];
                });

                $properties = [];

                foreach ($community->projects()->mainProject()->active()->get() as $project) {
                    if ($project->properties != null && count($project->properties) > 0) {
                        foreach ($project->properties()->active()->get() as $property) {

                            if ($property->source == 'xml') {
                                $banner_image = $property->property_banner;
                            } else {
                                $banner_image = $property->mainImage;
                            }

                            array_push($properties, [
                                'id' => "property_" . $property->id,
                                'name' => $property->name,
                                'slug' => $property->slug,
                                'property_banner' => $banner_image,
                                'accommodation' => $property->accommodations ? $property->accommodations->name : '',
                                'community' => $property->communities ? $property->communities->name : '',
                                'bedrooms' => $property->bedrooms,
                                'bathrooms' => $property->bathrooms,
                                'area' => $property->area,
                                'unit_measure' => $property->unit_measure,
                                'price' => $property->price,
                            ]);
                        }
                    }
                }
                $singleCommunity->properties = $properties;

                // if(count($community->stats) > 0 ){
                //     $singleCommunity->statValues = $community->stats[0]->values->map(function($stat){
                //         return [
                //             'id'=>"stat_".$stat->id,
                //             'key'=>$stat->key,
                //             'value'=>$stat->value,
                //         ];
                //     });
                // }else{
                //     $singleCommunity->statValues = [];
                // }
                $singleCommunity->nearbyCommunities = $nearbyCommunities;

                if ($community->meta_title) {
                    $singleCommunity->meta_title = $community->meta_title;
                } else {
                    $singleCommunity->meta_title = WebsiteSetting::getSetting('website_name') ? WebsiteSetting::getSetting('website_name') : '';
                }
                if ($community->meta_description) {
                    $singleCommunity->meta_description = $community->meta_description;
                } else {
                    $singleCommunity->meta_description = WebsiteSetting::getSetting('description') ? WebsiteSetting::getSetting('description') : '';
                }

                if ($community->meta_keyword) {
                    $singleCommunity->meta_keyword = $community->meta_keywords;
                } else {
                    $singleCommunity->meta_keyword = WebsiteSetting::getSetting('keywords') ? WebsiteSetting::getSetting('keywords') : '';
                }

                return $this->success('Single Community', $singleCommunity, 200);
            } else {
                return $this->success('Single Community', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function singleCommunityDetail($slug)
    {
        try {
            if (Community::where('slug', $slug)->exists()) {
                $community = Community::with(['highlights', 'amenities'])->where('slug', $slug)->first();
                $community = new CommunityResource($community);
                return $this->success('Single Community', $community, 200);
            } else {
                return $this->success('Single Community', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
}
