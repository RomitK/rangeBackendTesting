<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Developer,
    WebsiteSetting,
    Property,
    Project,
    CompletionStatus,
    Community,
    Accommodation
};
use DB;
use App\Http\Resources\{
    DeveloperListResource,
    SingleDeveloperResource
};

class DeveloperController extends Controller
{

    public function index(Request $request)
    {
        try {
            $collection = Developer::active()->approved();


            if (isset($request->completion_status_id) && $request->completion_status_id != null) {
                $collection->whereHas('projects', function ($q) use ($request) {
                    $q->where('completion_status_id', $request->completion_status_id);
                });
            }

            if (isset($request->community_id) && $request->community_id != null) {

                $collection->whereHas('communities', function ($q) use ($request) {
                    $q->where('community_id', $request->community_id);
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

            $developers = $collection->orderByRaw('ISNULL(developerOrder)')->orderBy('developerOrder', 'asc')->paginate(100);
            $developers = DeveloperListResource::collection($developers)->response()->getData(true);
            return $this->success('All Developers', $developers, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function developerOptions()
    {
        try {
            $developers = Developer::whereHas('communities')->active()->approved()->orderBy('name')->get()->map(function ($developer) {
                return [
                    'value' => $developer->id,
                    'label' => $developer->name
                ];
            });
            $developers->prepend([
                'value' => '',
                'label' => 'All'
            ]);
            return $this->success('Developer Options', $developers, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function singleDeveloper($slug, $request)
    {
        try {

            if (Developer::where('slug', $slug)->exists()) {
                $developer = Developer::where('slug', $slug)->first();

                $projects = [];
                $properties = [];

                foreach ($developer->communities as $community) {
                    foreach ($community->projects()->mainProject()->approved()->active()->select('id', 'title', 'slug')->get() as $project) {
                        array_push($projects, [
                            'id' => "project_" . $project->id,
                            'title' => $project->title,
                            'slug' => $project->slug,
                            'mainImage' => $project->mainImage,
                        ]);

                        if ($project->properties != null && count($project->properties) > 0) {
                            foreach ($project->properties()->approved()->active()->get() as $property) {

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
                }

                $singleDeveloper = (object)[];

                $singleDeveloper->id = "developer-" . $developer->id;
                $singleDeveloper->name = $developer->name;
                $singleDeveloper->slug = $developer->slug;
                $singleDeveloper->imageGallery = $developer->gallery;
                $singleDeveloper->longDescription = $developer->long_description->render();
                $singleDeveloper->shortDescription = $developer->short_description->render();
                // $properties = $developer->properties->map(function($property){

                //     return [
                //         'id'=>"property_".$property->id,
                //         'name'=>$property->name,
                //         'slug'=>$property->slug,
                //         'property_banner'=> $property->property_banner,
                //         'accommodation' => $property->accommodations? $property->accommodations->name:'',
                //         'community'=> $property->communities ? $property->communities->name: '',
                //         'bedrooms'=>$property->bedrooms,
                //         'bathrooms'=>$property->bathrooms,
                //         'area'=>$property->area,
                //         'unit_measure'=>$property->unit_measure,
                //         'price'=>$property->price,
                //     ];
                // })->take(12);


                // $singleDeveloper->properties = $properties->all();


                $singleDeveloper->properties = $properties;

                $singleDeveloper->communities = $developer->communityDevelopers()->where('is_approved', config('constants.approved'))->latest()->get()->map(function ($project) {

                    return [
                        'id' => "community_" . $project->id,
                        'name' => $project->name,
                        'slug' => $project->slug,
                        'mainImage' => $project->mainImage,
                    ];
                });


                // $singleDeveloper->projects = $developer->projects->map(function($project){
                //     return [
                //         'id'=>"project_".$project->id,
                //         'title'=>$project->title,
                //         'slug'=>$project->slug,
                //         'mainImage'=> $project->mainImage,
                //     ];
                // });


                $singleDeveloper->newProjects = Project::select('id', 'title', 'slug')->mainProject()->where('developer_id', $developer->id)->home()->active()->latest()->get()->map(function ($project) {
                    return [
                        'id' => 'newProject_' . $project->id,
                        'value' => $project->slug,
                        'label' => $project->title,
                    ];
                });


                $mapProjects = Project::mainProject()->where('developer_id', $developer->id)->active()->latest()->get();

                foreach ($mapProjects as $key => $value) {
                    $minBed = $value->subProjects->min('bedrooms');
                    $maxBed = $value->subProjects->max('bedrooms');
                    if ($minBed != $maxBed) {
                        $bedroom = $minBed . "-" . $maxBed;
                    } else {
                        $bedroom = $minBed;
                    }
                    $value->setAttribute('lat', (float)$value->address_latitude);
                    $value->setAttribute('lng', (float)$value->address_longitude);
                    $value->setAttribute('accommodationName', $value->accommodation ? $value->accommodation->name : null);
                    $value->setAttribute('completionStatusName', $value->completionStatus ? $value->completionStatus->name : null);
                    $value->setAttribute('starting_price', $value->subProjects->min('starting_price'));
                    $value->setAttribute('bedrooms', $bedroom);
                    $value->setAttribute('area', $value->subProjects->min('area'));
                }


                $mapProjects = $mapProjects->toJson();
                $singleDeveloper->mapProjects = $mapProjects;

                $singleDeveloper->projects = $projects;


                if ($developer->meta_title) {
                    $singleDeveloper->meta_title = $developer->meta_title;
                } else {
                    $singleDeveloper->meta_title = WebsiteSetting::getSetting('website_name') ? WebsiteSetting::getSetting('website_name') : '';
                }
                if ($developer->meta_description) {
                    $singleDeveloper->meta_description = $developer->meta_description;
                } else {
                    $singleDeveloper->meta_description = WebsiteSetting::getSetting('description') ? WebsiteSetting::getSetting('description') : '';
                }

                if ($developer->meta_keywords) {
                    $singleDeveloper->meta_keywords = $developer->meta_keywords;
                } else {
                    $singleDeveloper->meta_keywords = WebsiteSetting::getSetting('keywords') ? WebsiteSetting::getSetting('keywords') : '';
                }




                return $this->success('Single Developer', $singleDeveloper, 200);
            } else {
                return $this->success('Single Developer', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function singleDeveloperDetail($slug, Request $request)
    {
        //dd($request->all());
        try {
            if (Developer::where('slug', $slug)->exists()) {
                $developer = Developer::with(['communityDevelopers'])->where('slug', $slug)->first();
                $developer = new SingleDeveloperResource($developer);



                return $this->success('Single Developer', $developer, 200);

                $projects = [];
                $properties = [];

                foreach ($developer->communities as $community) {
                    foreach ($community->projects()->mainProject()->approved()->active()->select('id', 'title', 'slug')->get() as $project) {
                        array_push($projects, [
                            'id' => "project_" . $project->id,
                            'title' => $project->title,
                            'slug' => $project->slug,
                            'mainImage' => $project->mainImage,
                        ]);

                        if ($project->properties != null && count($project->properties) > 0) {
                            foreach ($project->properties()->approved()->active()->get() as $property) {

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
                }

                $singleDeveloper = (object)[];

                $singleDeveloper->id = "developer-" . $developer->id;
                $singleDeveloper->name = $developer->name;
                $singleDeveloper->slug = $developer->slug;
                $singleDeveloper->imageGallery = $developer->gallery;
                $singleDeveloper->longDescription = $developer->long_description->render();
                $singleDeveloper->shortDescription = $developer->short_description->render();
                // $properties = $developer->properties->map(function($property){

                //     return [
                //         'id'=>"property_".$property->id,
                //         'name'=>$property->name,
                //         'slug'=>$property->slug,
                //         'property_banner'=> $property->property_banner,
                //         'accommodation' => $property->accommodations? $property->accommodations->name:'',
                //         'community'=> $property->communities ? $property->communities->name: '',
                //         'bedrooms'=>$property->bedrooms,
                //         'bathrooms'=>$property->bathrooms,
                //         'area'=>$property->area,
                //         'unit_measure'=>$property->unit_measure,
                //         'price'=>$property->price,
                //     ];
                // })->take(12);


                // $singleDeveloper->properties = $properties->all();


                $singleDeveloper->properties = $properties;

                $singleDeveloper->communities = $developer->communityDevelopers()->where('is_approved', config('constants.approved'))->latest()->get()->map(function ($project) {

                    return [
                        'id' => "community_" . $project->id,
                        'name' => $project->name,
                        'slug' => $project->slug,
                        'mainImage' => $project->mainImage,
                    ];
                });


                // $singleDeveloper->projects = $developer->projects->map(function($project){
                //     return [
                //         'id'=>"project_".$project->id,
                //         'title'=>$project->title,
                //         'slug'=>$project->slug,
                //         'mainImage'=> $project->mainImage,
                //     ];
                // });


                $singleDeveloper->newProjects = Project::select('id', 'title', 'slug')->mainProject()->where('developer_id', $developer->id)->home()->active()->latest()->get()->map(function ($project) {
                    return [
                        'id' => 'newProject_' . $project->id,
                        'value' => $project->slug,
                        'label' => $project->title,
                    ];
                });


                $mapProjects = Project::mainProject()->where('developer_id', $developer->id)->active()->latest()->get();

                foreach ($mapProjects as $key => $value) {
                    $minBed = $value->subProjects->min('bedrooms');
                    $maxBed = $value->subProjects->max('bedrooms');
                    if ($minBed != $maxBed) {
                        $bedroom = $minBed . "-" . $maxBed;
                    } else {
                        $bedroom = $minBed;
                    }
                    $value->setAttribute('lat', (float)$value->address_latitude);
                    $value->setAttribute('lng', (float)$value->address_longitude);
                    $value->setAttribute('accommodationName', $value->accommodation ? $value->accommodation->name : null);
                    $value->setAttribute('completionStatusName', $value->completionStatus ? $value->completionStatus->name : null);
                    $value->setAttribute('starting_price', $value->subProjects->min('starting_price'));
                    $value->setAttribute('bedrooms', $bedroom);
                    $value->setAttribute('area', $value->subProjects->min('area'));
                }


                $mapProjects = $mapProjects->toJson();
                $singleDeveloper->mapProjects = $mapProjects;

                $singleDeveloper->projects = $projects;


                if ($developer->meta_title) {
                    $singleDeveloper->meta_title = $developer->meta_title;
                } else {
                    $singleDeveloper->meta_title = WebsiteSetting::getSetting('website_name') ? WebsiteSetting::getSetting('website_name') : '';
                }
                if ($developer->meta_description) {
                    $singleDeveloper->meta_description = $developer->meta_description;
                } else {
                    $singleDeveloper->meta_description = WebsiteSetting::getSetting('description') ? WebsiteSetting::getSetting('description') : '';
                }

                if ($developer->meta_keywords) {
                    $singleDeveloper->meta_keywords = $developer->meta_keywords;
                } else {
                    $singleDeveloper->meta_keywords = WebsiteSetting::getSetting('keywords') ? WebsiteSetting::getSetting('keywords') : '';
                }




                return $this->success('Single Developer', $singleDeveloper, 200);
            } else {
                return $this->success('Single Developer', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function singleDeveloperMeta($slug)
    {
        try {
            if (Developer::where('slug', $slug)->exists()) {
                $developer = DB::table('developers')->select('meta_title', 'name', 'meta_description', 'meta_keywords')->where('slug', $slug)->first();
                $singleDeveloper = (object)[];

                if ($developer->meta_title) {
                    $singleDeveloper->meta_title = $developer->meta_title;
                } else {
                    $singleCommunity->meta_title = WebsiteSetting::getSetting('website_name') ? WebsiteSetting::getSetting('website_name') : '';
                }
                if ($developer->meta_description) {
                    $singleDeveloper->meta_description = $developer->meta_description;
                } else {
                    $singleDeveloper->meta_description = WebsiteSetting::getSetting('description') ? WebsiteSetting::getSetting('description') : '';
                }

                if ($developer->meta_keywords) {
                    $singleDeveloper->meta_keywords = $developer->meta_keywords;
                } else {
                    $singleDeveloper->meta_keywords = WebsiteSetting::getSetting('keywords') ? WebsiteSetting::getSetting('keywords') : '';
                }

                return $this->success('Single Developer Meta', $singleDeveloper, 200);
            } else {
                return $this->success('Single Developer Meta', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
}
