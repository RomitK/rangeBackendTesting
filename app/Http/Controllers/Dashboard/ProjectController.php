<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Dashboard\Project\{
    ProjectRequest,
    ProjectPaymentRequest
};
use Illuminate\Support\Str;
use App\Models\{
    Property,
    Amenity,
    Accommodation,
    Category,
    CompletionStatus,
    Community,
    Developer,
    Feature,
    OfferType,
    Agent,
    PropertyBedroom,
    PropertyDetail,
    Subcommunity,
    Project,
    TagCategory,
    MetaDetail,
    ProjectAmenity,
    Highlight,
    ProjectDetail,
    User
};
use App\Jobs\{
    StoreProjectBrochure
};
use Auth;
use DB;
use PDF;

class ProjectController extends Controller
{
    function __construct()
    {
        $this->middleware(
            'permission:' . config('constants.Permissions.offplan'),
            [
                'only' => [
                    'index', 'create', 'edit', 'update', 'destroy', 'mediaDestroy',
                    'payments', 'createPayment', 'storePayment', 'editPayment', 'updatePayment', 'destroyPayment'
                ]
            ]
        );
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $projects=Project::mainProject()->with('subProjects')->get();

        $page_size = 25;
        $current_page = isset($request->item) ? $request->item : $page_size;
        if (isset($request->page)) {
            $sr_no_start = ($request->page * $current_page) - $current_page + 1;
        } else {
            $sr_no_start = 1;
        }

        $collection = Project::mainProject()->with('user');

        if (isset($request->status)) {
            $collection->where('status', $request->status);
        }
        if (isset($request->completion_status_ids)) {
            $collection->whereIn('completion_status_id', $request->completion_status_ids);
        }

        if (isset($request->community_ids)) {
            $collection->whereIn('community_id', $request->community_ids);
        }
        if (isset($request->developer_ids)) {
            $collection->whereIn('developer_id', $request->developer_ids);
        }
        if (isset($request->updated_user_ids)) {
            $collection->whereIn('updated_by', $request->updated_user_ids);
        }

        if (isset($request->added_user_ids)) {
            $collection->whereIn('user_id', $request->added_user_ids);
        }

        if (isset($request->display_on_home)) {
            $collection->where('is_display_home', $request->display_on_home);
        }

        if (isset($request->keyword)) {
            $keyword = $request->keyword;
            $collection->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%$keyword%")
                    ->orWhere('projects.reference_number', 'like', "%$keyword%")
                    ->orWhere('projects.permit_number', 'like', "%$keyword%");
            });
        }
        if (isset($request->is_approved)) {
            $collection->where('is_approved', $request->is_approved);
        }
        if (isset($request->updated_brochure)) {
            $collection->where('updated_brochure', $request->updated_brochure);
        }

        if (isset($request->orderby)) {
            $orderBy = $request->input('orderby', 'created_at'); // default_column is the default field to sort by
            $direction = $request->input('direction', 'asc'); // Default sorting direction
            $projects = $collection->orderByRaw('ISNULL(projectOrder)')->orderBy($orderBy, $direction)->paginate($current_page);
        } else {
            $projects = $collection->latest()->paginate($current_page);
        }



        $completionStatuses = CompletionStatus::active()->where('for_property', 0)->latest()->pluck('name', 'id');
        $completionStatuses->prepend('All', '');

        $accommodations = Accommodation::latest()->pluck('name', 'id');
        $accommodations->prepend('All', '');

        $communities = Community::latest()->pluck('name', 'id');
        $communities->prepend('All', '');

        $developers = Developer::latest()->pluck('name', 'id');
        $developers->prepend('All', '');

        $users = User::latest()->pluck('name', 'id');
        $users->prepend('All', '');
        return view('dashboard.realEstate.projects.index', compact(
            'projects',
            'sr_no_start',
            'completionStatuses',
            'accommodations',
            'communities',
            'current_page',
            'developers',
            'users'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $amenities = Amenity::active()->latest()->get();
        $accommodations = Accommodation::active()->get();
        $communities = Community::latest()->get();
        $developers = Developer::active()->latest()->get();
        $agents = Agent::active()->latest()->get();
        // $tags = TagCategory::projectTag()->active()->latest()->get();
        // $highlights = Highlight::active()->latest()->get();
        $completionStatuses = CompletionStatus::active()->where('for_property', 0)->latest()->get();
        return view('dashboard.realEstate.projects.create', compact('completionStatuses', 'agents', 'amenities', 'accommodations', 'communities', 'developers'));
    }

    public function generateBrochure($project)
    {
        try {
            //$project = Project::with('developer', 'mainCommunity', 'subProjects')->where('slug', $slug)->first();
            $minBed = $project->subProjects->min('bedrooms');
            $maxBed = $project->subProjects->max('bedrooms');
            if ($minBed != $maxBed) {
                if ($maxBed === "Studio") {
                    $bedroom = $maxBed . "-" . $minBed;
                } else {
                    $bedroom = $minBed . "-" . $maxBed;
                }
            } else {
                $bedroom = $minBed;
            }
            $area_unit = 'sq ft';

            $starting_price = 0;
            $dateStr = $project->completion_date;
            $month = date("n", strtotime($dateStr));
            $yearQuarter = ceil($month / 3);

            view()->share([
                'project' => $project,
                'area_unit' => $area_unit,
                'starting_price' => count($project->subProjects) > 0 ? $project->subProjects->where('starting_price', $project->subProjects->min('starting_price'))->first()->starting_price : 0,
                'bedrooms' => $bedroom,
                'handOver' => "Q" . $yearQuarter . " " . date("Y", strtotime($dateStr)),
                'communityName' => $project->mainCommunity ? $project->mainCommunity->name : '',

            ]);
            $pdf = PDF::loadView('pdf.projectBrochure');
            return $pdf->download($project->title . ' Brochure.pdf');
            // return $pdf->download($project->title.' Brochure.pdf');
            // $pdf = PDF::loadView('brochures.template', compact('project'));
            return $pdf->output();
        } catch (\Exception $error) {
            dd($error->getMessage(),);
        }
    }

    public function updateBrochure(Project $project)
    {
        try {

            $minBed = $project->subProjects->min('bedrooms');
            $maxBed = $project->subProjects->max('bedrooms');
            if ($minBed != $maxBed) {
                if ($maxBed === "Studio") {
                    $bedroom = $maxBed . "-" . $minBed;
                } else {
                    $bedroom = $minBed . "-" . $maxBed;
                }
            } else {
                $bedroom = $minBed;
            }
            $area_unit = 'sq ft';

            $starting_price = 0;
            $dateStr = $project->completion_date;
            $month = date("n", strtotime($dateStr));
            $yearQuarter = ceil($month / 3);

            view()->share([
                'project' => $project,
                'area_unit' => $area_unit,
                'starting_price' => count($project->subProjects) > 0 ? $project->subProjects->where('starting_price', $project->subProjects->min('starting_price'))->first()->starting_price : 0,
                'bedrooms' => $bedroom,
                'handOver' => "Q" . $yearQuarter . " " . date("Y", strtotime($dateStr)),
                'communityName' => $project->mainCommunity ? $project->mainCommunity->name : '',

            ]);
            $pdf = PDF::loadView('pdf.projectBrochure');
            //return $pdf->stream();
            //return $pdf->download($project->title.' Brochure.pdf');

            // $pdfContent = $this->generateBrochure($project);
            $pdfContent = $pdf->output();


            $project->clearMediaCollection('brochures');

            $project->addMediaFromString($pdfContent)
                ->usingFileName($project->title . '-brochure.pdf')
                ->toMediaCollection('brochures', 'projectFiles');

            $project->save();

            $project->brochure_link = $project->brochure;
            $project->updated_brochure = 1;
            $project->save();

            return redirect()->route('dashboard.projects.index')->with('success', 'Project Brchure has been updated successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.projects.index')->with('error', $error->getMessage());
        }
    }
    public function viewBrochure(Project $project)
    {
        dd($project);
    }
    public function mainImage()
    {
        foreach (Project::where('banner_image', '!=', null)->latest()->get() as $project) {
            $project->banner_image = $project->mainImage;
            $project->save();
            echo "project" . $project->id;
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
        DB::beginTransaction();
        try {

            $titleArray = explode(' ', $request->title);
            $sub_title = $titleArray[0];

            $subTitle1 = array_shift($titleArray);
            $sub_title_1 = implode(" ",  $titleArray);

            $project = new Project;
            $project->title = $request->title;
            $project->sub_title = $sub_title;
            $project->used_for = $request->used_for;
            $project->sub_title_1 = $sub_title_1;
            $project->status = $request->status;
            $project->project_source = 'crm';
            $project->is_parent_project = 1;
            $project->is_new_launch = $request->is_new_launch;
            $project->reference_number = $request->reference_number;
            $project->permit_number = $request->permit_number;
            $project->is_featured = $request->is_featured;
            $project->is_display_home = $request->is_display_home;
            $project->starting_price = $request->starting_price;
            $project->completion_date = $request->completion_date;
            $project->bathrooms = $request->bathrooms;
            $project->bedrooms = $request->bedrooms;
            $project->area = $request->area;
            $project->area_unit = $request->area_unit;
            $project->features_description = $request->features_description;
            $project->address = $request->address;
            $project->address_latitude = $request->address_latitude;
            $project->address_longitude = $request->address_longitude;
            $project->meta_title = $request->meta_title;
            $project->meta_description = $request->meta_description;
            $project->meta_keywords = $request->meta_keywords;
            $project->emirate = $request->emirate;

            if ($request->has('completion_status_id')) {
                $project->completionStatus()->associate($request->completion_status_id);
            }
            if ($request->has('starting_price_highlight')) {
                $project->starting_price_highlight = $request->starting_price_highlight;
            }
            if ($request->has('completion_date_highlight')) {
                $project->completion_date_highlight = $request->completion_date_highlight;
            }
            if ($request->has('area_highlight')) {

                $project->area_highlight = $request->area_highlight;
            }
            if ($request->has('accommodation_id')) {
                $project->accommodation_id = $request->accommodation_id;
            }
            if ($request->has('community_id_highlight')) {
                $project->community_id_highlight = $request->community_id_highlight;
            }
            if ($request->has('agent_id')) {
                $project->agent()->associate($request->agent_id);
            }
            if ($request->has('developer_id')) {
                $project->developer()->associate($request->developer_id);
            }
            if ($request->has('main_community_id')) {
                $project->mainCommunity()->associate($request->main_community_id);
            }
            // if($request->has('sub_community_id')){
            //     $project->subCommunity()->associate($request->sub_community_id);
            // }
            $parentCommunity = null;
            $childCommunity = null;

            if ($request->main_community_id) {
                $parentCommunity = Community::find($request->main_community_id);
                $project->search_keyword = $request->name . "(" . $parentCommunity->name . ", " . $request->emirate . ")";

                // if($request->has('sub_community_id')){
                //     $childCommunity = Community::find($request->sub_community_id);

                //     $project->search_keyword = $request->name ."(".$childCommunity->name.", ". $parentCommunity->name.", ".$request->emirate.")";
                // }

            } else {
                $project->search_keyword = $request->name . "(" . $request->emirate . ")";
            }


            if ($request->hasFile('mainImage')) {
                $img =  $request->file('mainImage');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title) . '.' . $ext;

                $project->addMediaFromRequest('mainImage')->usingFileName($imageName)->toMediaCollection('mainImages', 'projectFiles');
            }

            if ($request->hasFile('clusterPlan')) {
                $img =  $request->file('clusterPlan');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title) . '.' . $ext;

                $project->addMediaFromRequest('clusterPlan')->usingFileName($imageName)->toMediaCollection('clusterPlans', 'projectFiles');
            }


            if ($request->hasFile('video')) {
                $video =  $request->file('video');
                $ext = $video->getClientOriginalExtension();
                $videoName =  Str::slug($request->title) . '.' . $ext;
                $project->addMediaFromRequest('video')->usingFileName($videoName)->toMediaCollection('videos', 'projectFiles');
            }
            // if ($request->hasFile('exteriorGallery')) {
            //     foreach($request->exteriorGallery as $img)
            //     {
            //         $project->addMedia($img)->toMediaCollection('exteriorGallery', 'projectFiles');
            //     }
            // }


            if ($request->has('exteriorGallery')) {
                foreach ($request->exteriorGallery as $key => $img) {
                    if (array_key_exists("file", $img) && $img['file']) {
                        $title = $img['title'] ?? $request->title;
                        $order =  $img['order'] ?? null;

                        $project->addMedia($img['file'])
                            ->withCustomProperties([
                                'title' => $title,
                                'order' => $order
                            ])
                            ->toMediaCollection('exteriorGallery', 'projectFiles');
                    }
                }
            }

            if ($request->has('interiorGallery')) {
                foreach ($request->interiorGallery as $key => $img) {
                    if (array_key_exists("file", $img) && $img['file']) {
                        $title = $img['title'] ?? $request->title;
                        $order =  $img['order'] ?? null;

                        $project->addMedia($img['file'])
                            ->withCustomProperties([
                                'title' => $title,
                                'order' => $order
                            ])
                            ->toMediaCollection('interiorGallery', 'projectFiles');
                    }
                }
            }


            // if ($request->hasFile('interiorGallery')) {

            //     foreach($request->interiorGallery as $img)
            //     {
            //         $project->addMedia($img)->toMediaCollection('interiorGallery', 'projectFiles');
            //     }
            // }

            if ($request->hasFile('brochure')) {
                $brochure =  $request->file('brochure');
                $ext = $brochure->getClientOriginalExtension();
                $brochureName =  Str::slug($request->title) . '._brochure.' . $ext;
                $project->addMediaFromRequest('brochure')->usingFileName($brochureName)->toMediaCollection('brochures', 'projectFiles');
            }

            if ($request->hasFile('saleOffer')) {
                $img =  $request->file('saleOffer');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name) . '.' . $ext;

                $project->addMediaFromRequest('saleOffer')->usingFileName($imageName)->toMediaCollection('saleOffers', 'projectFiles');
            }

            if ($request->hasFile('factsheet')) {
                $factsheet =  $request->file('factsheet');
                $ext = $factsheet->getClientOriginalExtension();
                $factsheetName =  Str::slug($request->title) . '_factsheet.' . $ext;
                $project->addMediaFromRequest('factsheet')->usingFileName($factsheetName)->toMediaCollection('factsheets', 'projectFiles');
            }
            if ($request->hasFile('paymentPlan')) {
                $paymentPlan =  $request->file('paymentPlan');
                $ext = $paymentPlan->getClientOriginalExtension();
                $paymentPlantName =  Str::slug($request->title) . '_paymentPlan.' . $ext;
                $project->addMediaFromRequest('paymentPlan')->usingFileName($paymentPlantName)->toMediaCollection('paymentPlans', 'projectFiles');
            }

            $project->short_description = $request->short_description;
            $project->long_description = $request->long_description;
            $project->user_id = Auth::user()->id;
            $project->projectOrder = $request->projectOrder;

            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                $project->is_approved = config('constants.approved');
                $project->approval_id = Auth::user()->id;
            } else {
                $project->is_approved = config('constants.requested');
            }
            $project->save();

            $reference_prefix = 'RIPI_' . strtoupper(substr(Str::slug($project->developer->name), 0, 3));
            $nextInvoiceNumber = Project::getNextReferenceNumber($reference_prefix);
            $project->reference_number = $reference_prefix . "_" . $nextInvoiceNumber;
            $project->save();
            $project->banner_image = $project->mainImage;
            $project->save();

            if (isset($request->detailsKey)) {
                foreach ($request->detailsKey as $key => $detKey) {
                    if (!empty($detKey)) {
                        $project->propertyDetails()->attach($detKey, ['value' => $request->detailsName[$key]]);
                    }
                }
            }
            // if($request->has('accommodationIds')){
            //     $project->accommodations()->attach($request->accommodationIds);
            // }
            if ($request->has('highlightIds')) {
                $project->highlights()->attach($request->highlightIds);
            }

            if ($request->has('amenities')) {
                foreach ($request->amenities as $amenity) {
                    ProjectAmenity::insert([
                        'amenity_id' => $amenity,
                        'project_id' => $project->id
                    ]);
                }
            }
            if ($request->has('highlight_amenities')) {

                foreach ($request->highlight_amenities as $amenity) {
                    $project->amenities()->attach($amenity, ['highlighted' => 1]);
                }
            }
            if ($request->has('tagIds')) {
                foreach ($request->tagIds as $tag) {
                    $project->tags()->create(['tag_category_id' => $tag]);
                }
            }

            if (in_array($request->is_approved, [config('constants.approved')]) &&  in_array($request->status, [config('constants.active')])) {
                StoreProjectBrochure::dispatch($project->id);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Project has been created successfully.',
                'redirect' => route('dashboard.projects.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.projects.index'),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return redirect()->route('dubai-offplan', $project->slug);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {

        $amenities = Amenity::active()->latest()->get();
        $accommodations = Accommodation::active()->get();
        $communities = Community::latest()->get();
        $developers = Developer::latest()->get();
        $agents = Agent::active()->latest()->get();;
        $tags = TagCategory::projectTag()->active()->latest()->get();
        $highlights = Highlight::active()->latest()->get();
        $completionStatuses = CompletionStatus::active()->where('for_property', 0)->latest()->get();

        return view('dashboard.realEstate.projects.edit', compact('completionStatuses', 'highlights', 'tags', 'project', 'agents', 'amenities', 'accommodations', 'communities', 'developers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request, Project $project)
    {

        DB::beginTransaction();
        try {
            $titleArray = explode(' ', $request->title);
            $sub_title = $titleArray[0];

            $subTitle1 = array_shift($titleArray);
            $sub_title_1 = implode(" ",  $titleArray);

            $project->title = $request->title;
            $project->sub_title = $sub_title;
            $project->sub_title_1 = $sub_title_1;
            $project->used_for = $request->used_for;
            $project->permit_number = $request->permit_number;
            $project->status = $request->status;
            $project->is_new_launch = $request->is_new_launch;
            $project->project_source = $request->project_source;
            $project->is_featured = $request->is_featured;
            $project->is_display_home = $request->is_display_home;
            $project->starting_price = $request->starting_price;
            $project->completion_date = $request->completion_date;
            $project->bathrooms = $request->bathrooms;
            $project->bedrooms = $request->bedrooms;
            $project->area = $request->area;
            $project->area_unit = $request->area_unit;
            $project->features_description = $request->features_description;
            $project->address = $request->address;
            $project->address_latitude = $request->address_latitude;
            $project->address_longitude = $request->address_longitude;
            $project->meta_title = $request->meta_title;
            $project->meta_description = $request->meta_description;
            $project->meta_keywords = $request->meta_keywords;
            $project->emirate = $request->emirate;


            if ($request->main_community_id) {
                $parentCommunity = Community::find($request->main_community_id);
                $project->search_keyword = $request->title . "(" . $parentCommunity->name . ", " . $request->emirate . ")";
            } else {
                $project->search_keyword = $request->title . "(" . $request->emirate . ")";
            }

            if ($request->has('completion_status_id')) {
                $project->completionStatus()->associate($request->completion_status_id);
            }

            if ($request->has('agent_id')) {
                $project->agent()->associate($request->agent_id);
            }
            if ($request->has('developer_id')) {
                $project->developer()->associate($request->developer_id);
            }
            if ($request->has('main_community_id')) {
                $project->mainCommunity()->associate($request->main_community_id);
            }

            if ($request->has('starting_price_highlight')) {
                $project->starting_price_highlight = $request->starting_price_highlight;
            } else {
                $project->starting_price_highlight = 0;
            }
            if ($request->has('completion_date_highlight')) {
                $project->completion_date_highlight = $request->completion_date_highlight;
            } else {
                $project->completion_date_highlight = 0;
            }
            if ($request->has('community_id_highlight')) {
                $project->community_id_highlight = $request->community_id_highlight;
            } else {
                $project->community_id_highlight = 0;
            }
            if ($request->has('area_highlight')) {

                $project->area_highlight = $request->area_highlight;
            } else {
                $project->area_highlight = 0;
            }
            if ($request->has('accommodation_id_highlight')) {
                $project->accommodation_id_highlight = $request->accommodation_id_highlight;
            } else {
                $project->accommodation_id_highlight = 0;
            }
            if ($request->has('accommodation_id')) {
                $project->accommodation_id = $request->accommodation_id;
            }
            if ($request->hasFile('mainImage')) {
                $project->clearMediaCollection('mainImages');
                $img =  $request->file('mainImage');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title) . '.' . $ext;

                $project->addMediaFromRequest('mainImage')->usingFileName($imageName)->toMediaCollection('mainImages', 'projectFiles');
            }

            if ($request->hasFile('clusterPlan')) {

                $project->clearMediaCollection('clusterPlans');
                $img =  $request->file('clusterPlan');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title) . '.' . $ext;

                $project->addMediaFromRequest('clusterPlan')->usingFileName($imageName)->toMediaCollection('clusterPlans', 'projectFiles');
            }

            if ($request->hasFile('video')) {
                $project->clearMediaCollection('videos');
                $video =  $request->file('video');
                $ext = $video->getClientOriginalExtension();
                $videoName =  Str::slug($request->title) . '.' . $ext;
                $project->addMediaFromRequest('video')->usingFileName($videoName)->toMediaCollection('videos', 'projectFiles');
            }


            if ($request->has('exteriorGallery')) {
                foreach ($request->exteriorGallery as $key => $img) {

                    $title = $img['title'] ?? $request->title;
                    $order =  $img['order'] ?? null;
                    if ($img['old_gallery_id'] > 0) {

                        $mediaItem = Media::find($img['old_gallery_id']);
                        $mediaItem->setCustomProperty('title', $title);
                        $mediaItem->setCustomProperty('order', $order);
                        $mediaItem->save();
                    } else {
                        if (array_key_exists("file", $img) && $img['file']) {
                            $project->addMedia($img['file'])
                                ->withCustomProperties([
                                    'title' => $title,
                                    'order' => $order
                                ])->toMediaCollection('exteriorGallery', 'projectFiles');
                        }
                    }
                }
            }

            if ($request->has('interiorGallery')) {
                foreach ($request->interiorGallery as $key => $img) {
                    $title = $img['title'] ?? $request->title;
                    $order =  $img['order'] ?? null;
                    if ($img['old_gallery_id'] > 0) {
                        $mediaItem = Media::find($img['old_gallery_id']);
                        $mediaItem->setCustomProperty('title', $title);
                        $mediaItem->setCustomProperty('order', $order);
                        $mediaItem->save();
                    } else {
                        if (array_key_exists("file", $img) && $img['file']) {
                            $project->addMedia($img['file'])
                                ->withCustomProperties([
                                    'title' => $title,
                                    'order' => $order
                                ])
                                ->toMediaCollection('interiorGallery', 'projectFiles');
                        }
                    }
                }
            }
            if ($request->hasFile('brochure')) {
                if ($project->brochure) {
                    $project->clearMediaCollection('brochures');
                }
                $brochure =  $request->file('brochure');
                $ext = $brochure->getClientOriginalExtension();
                $brochureName =  Str::slug($request->title) . '_brochure.' . $ext;

                $project->addMedia($brochure)->usingFileName($brochureName)->toMediaCollection('brochures', 'projectFiles');
            }

            if ($request->hasFile('saleOffer')) {
                $project->clearMediaCollection('saleOffers');
                $img =  $request->file('saleOffer');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title) . '.' . $ext;

                $project->addMediaFromRequest('saleOffer')->usingFileName($imageName)->toMediaCollection('saleOffers', 'projectFiles');
            }

            if ($request->hasFile('factsheet')) {
                if ($project->factsheet) {
                    $project->clearMediaCollection('factsheets');
                }
                $factsheet =  $request->file('factsheet');
                $ext = $factsheet->getClientOriginalExtension();
                $factsheetName =  Str::slug($request->title) . '_factsheet.' . $ext;
                $project->addMedia($factsheet)->usingFileName($factsheetName)->toMediaCollection('factsheets', 'projectFiles');
            }
            if ($request->hasFile('paymentPlan')) {
                if ($project->paymentPlan) {
                    $project->clearMediaCollection('paymentPlans');
                }
                $paymentPlan =  $request->file('paymentPlan');
                $ext = $paymentPlan->getClientOriginalExtension();
                $paymentPlantName =  Str::slug($request->title) . '_paymentPlan.' . $ext;
                $project->addMedia($paymentPlan)->usingFileName($paymentPlantName)->toMediaCollection('paymentPlans', 'projectFiles');
            }

            $project->short_description = $request->short_description;
            $project->long_description = $request->long_description;
            // $project->user_id = Auth::user()->id;


            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                $project->approval_id = Auth::user()->id;

                if (in_array($request->is_approved, ["approved", "rejected"])) {
                    $project->is_approved = $request->is_approved;
                }
            } else {
                $project->is_approved = "requested";
                $project->approval_id = null;
            }
            $project->updated_by = Auth::user()->id;
            $project->projectOrder = $request->projectOrder;

            $project->updated_brochure = 0;


            $project->save();
            $project->banner_image = $project->mainImage;

            $project->save();
            Property::where('project_id', $project->id)->update(['updated_brochure' => 0]);

            if ($project->status != '') {
                $project->properties()->update(['status' => $request->status]);
            }

            if ($request->has('highlightIds')) {
                $project->highlights()->detach();
                $project->highlights()->attach($request->highlightIds);
            } else {
                $project->highlights()->detach();
            }

            if ($request->has('amenities')) {
                ProjectAmenity::where('project_id', $project->id)->delete();

                foreach ($request->amenities as $amenity) {
                    ProjectAmenity::insert([
                        'amenity_id' => $amenity,
                        'project_id' => $project->id
                    ]);
                }
            }

            if ($request->has('tagIds')) {
                $project->tags()->delete();
                foreach ($request->tagIds as $tag) {
                    $project->tags()->create(['tag_category_id' => $tag]);
                }
            }

            if (in_array($request->is_approved, [config('constants.approved')]) &&  in_array($request->status, [config('constants.active')])) {
                Log::info("project update for brochue");
                StoreProjectBrochure::dispatch($project->id);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Project has been upated successfully.',
                'redirect' => route('dashboard.projects.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.projects.index'),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        try {
            $project->tags()->delete();
            $project->subProjects()->delete();
            $project->paymentPlans()->delete();
            $project->delete();
            return redirect()->route('dashboard.projects.index')->with('success', 'Project has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.projects.index')->with('error', $error->getMessage());
        }
    }
    public function mediaDestroy(Project $project, $media)
    {
        try {
            $project->deleteMedia($media);
            return redirect()->route('dashboard.projects.edit', $project->id)->with('success', 'Project Image has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.projects.edit', $project->id)->with('error', $error->getMessage());
        }
    }
    public function interiorMediasDestroy(Project $project)
    {
        try {
            $project->clearMediaCollection('interiorGallery');
            return redirect()->route('dashboard.projects.edit', $project->id)->with('success', 'Project Interior Gallery has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.projects.edit', $project->id)->with('error', $error->getMessage());
        }
    }
    public function exteriorMediasDestroy(Project $project)
    {
        try {
            $project->clearMediaCollection('exteriorGallery');
            return redirect()->route('dashboard.projects.edit', $project->id)->with('success', 'Project Interior Gallery has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.projects.edit', $project->id)->with('error', $error->getMessage());
        }
    }

    public function payments(Project $project)
    {
        return view('dashboard.realEstate.projects.paymentPlans.index', compact('project'));
    }
    public function createPayment(Project $project)
    {
        return view('dashboard.realEstate.projects.paymentPlans.create', compact('project'));
    }
    public function storePayment(Request $request, Project $project)
    {
        DB::beginTransaction();
        try {
            $request->name;
            $paymentPlan = new ProjectDetail();
            $paymentPlan->project_id  = $project->id;
            $paymentPlan->key = 'payment';
            $paymentPlan->value = $request->name;
            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                $paymentPlan->is_approved = config('constants.approved');
                $paymentPlan->approval_id = Auth::user()->id;
            } else {
                $paymentPlan->is_approved = config('constants.requested');
            }

            $paymentPlan->save();
            $input = $request->input('rows');

            foreach ($input as $row) {
                if (isset($row['key']) &&  isset($row['value'])) {
                    $newPaymentPlan = new MetaDetail();
                    $newPaymentPlan->detailable_id = $paymentPlan->id;
                    //$newPaymentPlan->name = $row['name'];
                    $newPaymentPlan->value = $row['value'];
                    $newPaymentPlan->key = $row['key'];
                    $newPaymentPlan->detailable_type = 'App\Models\ProjectDetail';
                    $newPaymentPlan->save();
                }
            }
            DB::commit();
            return redirect()->route('dashboard.projects.paymentPlans', $project->id)->with('success', 'Payment Plan has been created successfully.');
        } catch (\Exception $error) {
            return redirect()->back()->with('error', $error->getMessage());
        }
    }
    public function editPayment(Project $project, ProjectDetail $payment)
    {
        return view('dashboard.realEstate.projects.paymentPlans.edit', compact('payment', 'project'));
    }
    public function updatePayment(Request $request, Project $project,   ProjectDetail $payment)
    {

        DB::beginTransaction();
        try {
            $payment->value = $request->name;

            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                $payment->approval_id = Auth::user()->id;
            }

            $payment->is_approved = $request->is_approved;

            $payment->save();
            $input = $request->input('rows');
            // dd($input);
            foreach ($input as $row) {

                if (isset($row['value']) &&  isset($row['key'])) {
                    if ($row['old_payment_row_id'] > 0) {
                        $newPaymentPlan = MetaDetail::find($row['old_payment_row_id']);
                    } else {

                        $newPaymentPlan = new MetaDetail();
                        $newPaymentPlan->detailable_type = 'App\Models\ProjectDetail';
                        $newPaymentPlan->detailable_id = $payment->id;
                    }
                    //$newPaymentPlan->name = $row['name'];
                    $newPaymentPlan->value = $row['value'];
                    $newPaymentPlan->key = $row['key'];
                    $newPaymentPlan->save();
                }
            }
            DB::commit();
            return redirect()->route('dashboard.projects.paymentPlans', $project->id)->with('success', 'Payment Plan has been updated successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.projects.paymentPlans', $project->id)->with('error', $error->getMessage());
        }
    }
    public function deletePaymentPlanAjax($id)
    {
        $row = MetaDetail::find($id);
        $row->delete();
    }
    public function destroyPayment(Project $project, ProjectDetail $payment)
    {
        try {
            $payment->delete();
            return redirect()->route('dashboard.projects.paymentPlans', $project->id)->with('success', 'Payment Plan has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.projects.paymentPlans', $project->id)->with('error', $error->getMessage());
        }
    }
    public function subProjects(Request $request)
    {
        $project_id = $request->project_id;
        $subProjects = Project::where('parent_project_id', $project_id)->where('is_parent_project', 0)->select('id', 'title')->latest()->get();
        return response()->json([
            'subProjects' => $subProjects
        ]);
    }
    public function singleProjectDetail(Request $request)
    {
        $project = $request->project_id;
        $project = Project::with('amenities')->find($project);
        $amenities = $project->amenities->pluck('id');
        return response()->json([
            'project' => $project,
            'amenities' => $amenities
        ]);
    }
}
