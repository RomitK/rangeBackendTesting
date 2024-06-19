<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Http\Requests\Dashboard\Project\{
    SubProjectRequest,
    ProjectPaymentRequest
};
use App\Models\{
    Project,
    Amenity,
    Accommodation,
    MetaDetail
};
use DB;
use Auth;

class SubProjectController extends Controller
{
    protected $projectRepository;

    function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->middleware(
            'permission:' . config('constants.Permissions.real_estate'),
            [
                'only' => ['index', 'create', 'edit', 'update', 'destroy']
            ]
        );
        $this->projectRepository = $projectRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        return view('dashboard.realEstate.projects.sub.index', compact('project'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project)
    {
        $amenities = Amenity::active()->latest()->get();
        $accommodations = Accommodation::active()->latest()->get();
        $bedrooms = ['Studio', 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
        return view('dashboard.realEstate.projects.sub.create', compact('project', 'amenities', 'accommodations', 'bedrooms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubProjectRequest $request, Project $project)
    {

        try {
            $result = $this->projectRepository->subProjectStore($request, $project);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'redirect' => route('dashboard.projects.subProjects', $project->id),
                //  'project_id' => $result['project_id'],
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.projects.subProjects', $project->id),
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, Project $subProject)
    {
        $amenities = Amenity::active()->latest()->get();
        $accommodations = Accommodation::active()->latest()->get();
        $bedrooms = ['Studio', 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
        return view('dashboard.realEstate.projects.sub.edit', compact('amenities', 'project', 'subProject', 'accommodations', 'bedrooms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubProjectRequest $request, Project $project,  Project $subProject)
    {

        try {
            $result = $this->projectRepository->subProjectUpdate($request, $project, $subProject);
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'redirect' => route('dashboard.projects.subProjects', $project->id),
            ]);
        } catch (\Exception $error) {

            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.projects.subProjects', $project->id),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, Project $subProject)
    {
        try {

            if ($subProject->projectBedrooms->count() > 0) {
                $subProject->projectBedrooms()->delete();
            }
            $subProject->delete();
            return redirect()->route('dashboard.projects.subProjects', $project->id)->with('success', 'Sub Project has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.projects.subProjects', $project->id)->with('error', $error->getMessage());
        }
    }


    public function payments(Project $project, Project $subProject)
    {
        return view('dashboard.realEstate.projects.sub.paymentPlans.index', compact('project', 'subProject'));
    }
    public function createPayment(Project $project, Project $subProject)
    {
        return view('dashboard.realEstate.projects.sub.paymentPlans.create', compact('project', 'subProject'));
    }
    public function storePayment(ProjectPaymentRequest $request, Project $project, Project $subProject)
    {
        DB::beginTransaction();
        try {
            $subProject->paymentPlans()->create(['name' => $request->name, 'value' => $request->value, 'key' => $request->key]);
            DB::commit();
            return redirect()->route('dashboard.projects.subProjects.paymentPlans', [$project->id, $subProject->id])->with('success', 'Payment Plan has been created successfully.');
        } catch (\Exception $error) {
            return redirect()->back()->with('error', $error->getMessage());
        }
    }
    public function editPayment(Project $project, Project $subProject, MetaDetail $payment)
    {
        return view('dashboard.realEstate.projects.sub.paymentPlans.edit', compact('payment', 'project', 'subProject'));
    }
    public function updatePayment(ProjectPaymentRequest $request, Project $project, Project $subProject,   MetaDetail $payment)
    {
        try {
            $payment->name = $request->name;
            $payment->value = $request->value;
            $payment->key = $request->key;
            $payment->save();
            return redirect()->route('dashboard.projects.subProjects.paymentPlans', [$project->id, $subProject->id])->with('success', 'Payment Plan has been updated successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.projects.subProjects.paymentPlans', [$project->id,  $subProject->id])->with('error', $error->getMessage());
        }
    }
    public function destroyPayment(Project $project, Project $subProject, MetaDetail $payment)
    {
        try {
            $payment->delete();
            return redirect()->route('dashboard.projects.subProjects.paymentPlans', [$project->id,  $subProject->id])->with('success', 'Payment Plan has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.projects.subProjects.paymentPlans', [$project->id, $subProject->id])->with('error', $error->getMessage());
        }
    }
    public function floorplansDestroy(Project $project, Project $subProject)
    {
        try {

            $subProject->clearMediaCollection('floorplans');
            return redirect()->route('dashboard.projects.subProjects.edit', [$project->id, $subProject->id])->with('success', 'Floor Plan has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.projects.subProjects.edit', [$project->id, $subProject->id])->with('error', $error->getMessage());
        }
    }

    public function floorplanDestroy(Project $project, Project $subProject, $floorplan)
    {
        try {
            $subProject->deleteMedia($floorplan);
            return redirect()->route('dashboard.projects.subProjects.edit', [$project->id, $subProject->id])->with('success', 'Floor Plan has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.projects.subProjects.edit', [$project->id, $subProject->id])->with('error', $error->getMessage());
        }
    }
}
