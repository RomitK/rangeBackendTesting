<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Dashboard\Project\{
    ProjectRequest,
    SubProjectRequest,
    ProjectPaymentRequest,
};
use App\Http\Requests\Dashboard\{
    ProjectMetaRequest,
    
};
use App\Http\Requests\InventoryRequest;
use  App\Imports\{
    InventoryImport
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
    User,
    Currency
};
use App\Jobs\{
    StoreProjectBrochure,
    ProjectLogExportAndEmailData
};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use PDF;
use App\Exports\DLDTransaction;
use Maatwebsite\Excel\Facades\Excel;

class ProjectController extends Controller
{
    protected $projectRepository;

    function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->middleware(
            'permission:' . config('constants.Permissions.offplan') . '|' . config('constants.Permissions.seo'),
            [
                'only' => [
                    'index',
                    'create',
                    'edit',
                    'update',
                    'destroy',
                    'mediaDestroy',
                    'payments',
                    'createPayment',
                    'storePayment',
                    'editPayment',
                    'updatePayment',
                    'destroyPayment'
                ]
            ]
        );
        $this->projectRepository = $projectRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $result = $this->projectRepository->filterData($request);

        if ($request->has('export')) {
            // Handle export scenario
            return $result; // This will return the JSON response from your repository method
        } else {
            // Handle normal pagination scenario
            $projects = $result['projects'];
            $current_page = $result['current_page'];
            $sr_no_start = $result['sr_no_start'];


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
        $completionStatuses = CompletionStatus::active()->where('for_property', 0)->latest()->get();

        return view('dashboard.realEstate.projects.create', compact('completionStatuses', 'amenities', 'accommodations', 'communities'));
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

        $currency = 'AED';
        $exchange_rate = 1;
        if (isset($request->currency)) {
            $currenyExist = Currency::where('name', $request->currency)->exists();

            if ($currenyExist) {
                $currency = $request->currency;
                $exchange_rate = Currency::where('name', $request->currency)->first()->value;
            }
        }

        // Disable timestamps for this scope
        Project::withoutTimestamps(function () use ($project, $currency, $exchange_rate) {
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
                    'currency' => $currency,
                    'exchange_rate' => $exchange_rate,
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

                return redirect()->route('dashboard.projects.index')->with('success', 'Project Brochure has been updated successfully.');
            } catch (\Exception $error) {
                return redirect()->route('dashboard.projects.index')->with('error', $error->getMessage());
            }
        });
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
        try {
            $result = $this->projectRepository->storeData($request);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'redirect' => route('dashboard.projects.index'),
                'project_id' => $result['project_id'],
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

    public function inventory(Project $project)
    {
        return view('dashboard.realEstate.projects.inventory', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function inventoryUpdate(Request $request, Property $property)
    {
        try {
            Property::find($property);
            $result = $this->projectRepository->updateData($request, $property);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'redirect' => route('dashboard.projects.index'),
                //'project_id' => $result['project_id'], // If returned from repository
            ]);
        } catch (\Exception $error) {

            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.projects.index'),
            ]);
        }
    }

    public function inventoryUpdate1(Request $request, Property $property)
    {


        try {
            $project = Project::find($property->project_id);
            Log::info($project);
            if (isset($request->field) && isset($request->value)) {
                if ($request->field == 'price') {
                    if ($request->value != $property->price) {
                        $property->price = $request->value;
                        $project->inventory_update = Carbon::now();
                        $property->user_id = Auth::user()->id;
                        Log::info($property);
                        $property->save();
                        $project->save();
                        return response()->json([
                            'success' => true,
                            'message' => "Inventory has been updated Succesfully",
                        ]);
                    }
                } elseif ($request->field == 'website_status') {
                    if ($request->value != $property->website_status) {
                        $property->website_status = $request->value;
                        $project->inventory_update = Carbon::now();
                        $property->user_id = Auth::user()->id;
                        Log::info($property);
                        $property->save();
                        $project->save();
                        return response()->json([
                            'success' => true,
                            'message' => "Inventory has been updated Succesfully",
                        ]);
                    }
                } elseif ($request->field == 'area') {
                    if ($request->value != $property->area) {
                        $property->area = $request->value;
                        $project->inventory_update = Carbon::now();
                        $property->user_id = Auth::user()->id;
                        Log::info($property);
                        $property->save();
                        $project->save();
                        return response()->json([
                            'success' => true,
                            'message' => "Inventory has been updated Succesfully",
                        ]);
                    }
                }
            }
        } catch (\Exception $error) {

            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.projects.index'),
            ]);
        }
    }
    public function update(ProjectRequest $request, Project $project)
    {

        try {
            $result = $this->projectRepository->updateData($request, $project);
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'redirect' => route('dashboard.projects.index'),
                //'project_id' => $result['project_id'], // If returned from repository
            ]);
        } catch (\Exception $error) {

            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.projects.index'),
            ]);
        }
    }

    public function logs(Project $project, Request $request)
    {
        if (isset($request->export)) {
            $data = [
                'email' => Auth::user()->email,
                'userName' => Auth::user()->name,
                'project_id' => $project->id,
            ];

            ProjectLogExportAndEmailData::dispatch($data, $project);

            return redirect()->route('dashboard.projects.logs', $project->id)->with('success', 'Please Check Email, Log History has been sent');
        } else {
            return view('dashboard.realEstate.projects.logs.index', compact('project'));
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
            $project->subProjects()->delete();
            $project->paymentPlans()->delete();
            $project->delete();
            return redirect()->route('dashboard.projects.index')->with('success', 'Project has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.projects.index')->with('error', $error->getMessage());
        }
    }

    public function meta(Project $project)
    {
        return view('dashboard.realEstate.projects.meta', compact('project'));
    }
    public function updateMeta(ProjectMetaRequest $request, Project $project)
    {
        try {
            $result = $this->projectRepository->updateMetaData($request, $project);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'redirect' => route('dashboard.projects.index'),
                'project_id' => $result['project_id'],
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.projects.index'),
            ]);
        }
    }

    public function mediaDestroy(Project $project, $media)
    {
        try {
            Log::info("mediaDestroy-start" . Carbon::now());
            Log::info("project-" . $project->id . "project-approval" . $project->is_approved);
            Log::info("media-" . $media);
            $project->deleteMedia($media);
            Log::info("mediaDestroy-end" . Carbon::now());
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


    public function inventoryDownload(Request $request, Project $project)
    {
        DB::beginTransaction();
        try {
            $project =  Project::with(['subProjects', 'subProjects.accommodation', 'subProjects.properties'])->find($project->id);
            $projectName = $project->title . '-Inventory.xlsx';
            return  Excel::download(new DLDTransaction($project), $projectName);

            //  dd('projectId'.$project->id);
            DB::commit();
        } catch (\Exception $error) {
            return redirect()->back()->with('error', $error->getMessage());
        }
    }

    public function inventoryList(Project $project)
    {
        $today = Carbon::now();

        $project = $project->withCount([
            'properties as available_count' => function ($query) {
                $query->where('properties.website_status', config('constants.available'))->where('property_source', 'xml');
            },
            'properties as na_count' => function ($query) {
                $query->where('properties.website_status', config('constants.NA'))->where('property_source', 'xml');
            },
            'properties as requested_count' => function ($query) {
                $query->where('properties.website_status', config('constants.requested'))->where('property_source', 'xml');
            },
            'properties as rejected_count' => function ($query) {
                $query->where('properties.website_status', config('constants.rejected'))->where('property_source', 'xml');
            }
        ])
            ->where('projects.id', $project->id)
            ->with('user')
            //->selectRaw('projects.*, DATEDIFF(?, (SELECT MAX(inventory_update) FROM projects WHERE properties.project_id = projects.id)) as date_diff', [$today])
            ->first();

        return view('dashboard.realEstate.inventory.inventory', compact(
            'project',
        ));
    }

    public function inventoryCreate(Project $project)
    {
        $accommodations = Accommodation::active()->latest()->get();

        return view('dashboard.realEstate.inventory.create', compact(
            'accommodations',
            'project',
        ));
    }

    public function inventoryStore(SubProjectRequest $request, Project $project)
    {
        DB::beginTransaction();
        try {

            $subProject = new Project;

            // Set status, approval, and website status based on user role and request
            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                switch ($request->website_status) {
                    case config('constants.available'):
                        $subProject->is_approved = config('constants.approved');
                        $subProject->status = config('constants.active');
                        $subProject->approval_id = Auth::user()->id;
                        break;
                    case config('constants.NA'):
                        $subProject->is_approved = config('constants.approved');
                        $subProject->status = config('constants.Inactive');
                        $subProject->approval_id = Auth::user()->id;
                        break;
                    case config('constants.rejected'):
                        $subProject->is_approved = config('constants.rejected');
                        $subProject->status = config('constants.active');
                        $subProject->approval_id = Auth::user()->id;
                        break;
                    case config('constants.requested'):
                    default:
                        $subProject->is_approved = config('constants.requested');
                        $subProject->status = config('constants.active');
                        break;
                }
                $subProject->website_status = $request->website_status;
            } else {
                $subProject->is_approved = config('constants.requested');
                $subProject->status = config('constants.active');
                $subProject->website_status = $request->website_status;
            }
            $subProject->title = $request->title;
            $subProject->is_parent_project = 0;
            $subProject->parent_project_id = $project->id;
            $subProject->bedrooms = $request->bedrooms;
            $subProject->list_type = $request->list_type;
            $subProject->area = $request->area;
            $subProject->builtup_area = $request->builtup_area;
            $subProject->area_unit = $request->area_unit;
            $subProject->starting_price = $request->starting_price;
            $subProject->short_description = $request->short_description;
            $subProject->user_id = Auth::user()->id;
            $subProject->accommodation_id = $request->accommodation_id;
            $subProject->save();
            if ($request->hasFile('floorPlan')) {

                foreach ($request->floorPlan as $floorPlan) {
                    $subProject->addMedia($floorPlan)->toMediaCollection('floorPlans', 'projectFiles');
                }
            }
            $property = new Property;
            $property->name = $subProject->title;


            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                switch ($request->website_status) {
                    case config('constants.available'):
                        $property->is_approved = config('constants.approved');
                        $property->status = config('constants.active');
                        $property->approval_id = Auth::user()->id;
                        break;
                    case config('constants.NA'):
                        $property->is_approved = config('constants.approved');
                        $property->status = config('constants.Inactive');
                        $property->approval_id = Auth::user()->id;
                        break;
                    case config('constants.rejected'):
                        $property->is_approved = config('constants.rejected');
                        $property->status = config('constants.active');
                        $property->approval_id = Auth::user()->id;
                        break;
                    case config('constants.requested'):
                    default:
                        $property->is_approved = config('constants.requested');
                        $property->status = config('constants.active');
                        break;
                }
                $property->website_status = $request->website_status;
            } else {
                $property->is_approved = config('constants.requested');
                $property->status = config('constants.active');
                $property->website_status = $request->website_status;
            }

            if(!empty($project->qr_link)){
                $property->addMediaFromUrl($project->qr_link)->toMediaCollection('qrs', 'propertyFiles' );
            }

            $property->property_source = 'crm';
            $property->bathrooms = 0;
            $property->bedrooms = $subProject->bedrooms;
            $property->area = $subProject->area;
            $property->price = $subProject->starting_price;
            $property->builtup_area = $subProject->builtup_area;
            $property->project_id = $project->id;
            $property->sub_project_id = $subProject->id;
            $property->accommodation_id = $subProject->accommodation_id;
            $property->category_id = 8;
            $property->permit_number = $project->permit_number;

            $property->reference_number = generatePropertyUniqueCode('S');
            if ($project->completion_status_id == 289) { // if the project is under-construction then its offplan else ready
                $property->completion_status_id = 287;
            } else {
                $property->completion_status_id = 286;
            }


            $property->address_longitude = $project->address_longitude;
            $property->address_latitude = $project->address_latitude;
            $property->address = $project->address;
            $property->user_id = Auth::user()->id;
            $property->save();

            $property->save();
            if($property->qr){
                $property->qr_link = $property->qr;
            }
            $property->save();

           
            if (!empty($property->permit_number) && !empty($property->qr_link)) {
                if($property->qr_link){
                    $property->is_valid = 1;
                }else{
                    $property->is_valid = 0; // Optionally set to false if not valid
                }
               
            } else {
                $property->is_valid = 0; // Optionally set to false if not valid
            }
            $property->save();
            $property->amenities()->attach($project->amenities->pluck('id')->toArray());
            $originalAttributes = $property->getOriginal();
            $originalAttributes['short_description'] = null;
            $originalAttributes['description'] = null;

            $originalAttributes['amenityIds'] = $project->amenities->pluck('id')->toArray();


            // Log activity for developer creation
            $properties = json_encode([
                'old' => [],
                'new' => $originalAttributes,
                'updateAttribute' => [],
                'attribute' => []
            ]);
            logActivity('New Inventory Property has been created', $property->id, Property::class, $properties);

            $project = Project::find($project->id);
            $project->timestamps = false;
            $project->inventory_update = Carbon::now();
            $project->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inventory Item has been created successfully ',
                'redirect' => route('dashboard.inventoryReport.list', $project->id),
            ]);
        } catch (\Exception $error) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.inventoryReport.list', $project->id),
            ]);
        }
    }

}
