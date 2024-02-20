<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.real_estate'),
        ['only' => ['index','create', 'edit', 'update', 'destroy']
        ]);
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
        $bedrooms = ['Studio',1,2,3,4,5,6,7,8,9,10,11];
        return view('dashboard.realEstate.projects.sub.create', compact('project','amenities', 'accommodations', 'bedrooms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubProjectRequest $request, Project $project)
    {
        
        DB::beginTransaction();
        try{

            $subProject = new Project;
            $subProject->title = $request->title;
            $subProject->status = $request->status;
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
            
            if(in_array(Auth::user()->role, config('constants.isAdmin'))){
                $subProject->is_approved = config('constants.approved' );
                $subProject->approval_id = Auth::user()->id;
                
            }else{
                $subProject->is_approved = config('constants.requested' );
            }
            $subProject->save();
            // if($request->has('amenities')){
            //     $subProject->amenities()->attach($request->amenities);
            // }
            // if($request->has('accommodationIds')){
            //     $subProject->accommodations()->attach($request->accommodationIds);
            // }
            
            if ($request->hasFile('floorPlan')) {

                foreach($request->floorPlan as $floorPlan)
                {
                    $subProject->addMedia($floorPlan)->toMediaCollection('floorPlans', 'projectFiles');
                }
            }
            
            DB::commit();
            return redirect()->route('dashboard.projects.subProjects',$project->id )->with('success','Sub Project has been created successfully.');
        }catch(\Exception $error){
            dd($error->getMessage());
            return redirect()->back()->with('error',$error->getMessage());
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
        $bedrooms = ['Studio',1,2,3,4,5,6,7,8,9,10,11];
        return view('dashboard.realEstate.projects.sub.edit', compact('amenities','project', 'subProject', 'accommodations', 'bedrooms'));
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
        try{
            $subProject->title = $request->title;
            $subProject->status = $request->status;
            $subProject->is_parent_project = 0;
            $subProject->parent_project_id = $project->id;
            $subProject->bedrooms = $request->bedrooms;
            $subProject->area = $request->area;
            $subProject->builtup_area = $request->builtup_area;
            $subProject->area_unit = $request->area_unit;
            $subProject->list_type = $request->list_type;
            $subProject->starting_price = $request->starting_price;
            $subProject->accommodation_id = $request->accommodation_id;

            // if($request->has('amenities')){
            //     $subProject->amenities()->detach();
            //     $subProject->amenities()->attach($request->amenities);
            // }
           
            if ($request->hasFile('floorPlan')) {

                foreach($request->floorPlan as $floorPlan)
                {
                    $subProject->addMedia($floorPlan)->toMediaCollection('floorPlans', 'projectFiles');
                }
            }
            
             if(in_array(Auth::user()->role, config('constants.isAdmin'))){
                $subProject->approval_id = Auth::user()->id;
            }
            
            $subProject->is_approved = $request->is_approved;
            $subProject->save();
            
            return redirect()->route('dashboard.projects.subProjects',$project->id )->with('success','Sub Project has been created successfully.');
        }catch(\Exception $error){
            return redirect()->back()->with('error',$error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project , Project $subProject)
    {
        try{

            if($subProject->projectBedrooms->count() > 0){
                $subProject->projectBedrooms()->delete();
            }
            $subProject->delete();
            return redirect()->route('dashboard.projects.subProjects',$project->id )->with('success','Sub Project has been deleted successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.projects.subProjects',$project->id)->with('error',$error->getMessage());
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
        try{
            $subProject->paymentPlans()->create(['name'=>$request->name, 'value'=>$request->value, 'key'=> $request->key]);
            DB::commit();
            return redirect()->route('dashboard.projects.subProjects.paymentPlans',[$project->id, $subProject->id] )->with('success','Payment Plan has been created successfully.');
        }catch(\Exception $error){
            return redirect()->back()->with('error',$error->getMessage());
        }
    }
    public function editPayment(Project $project, Project $subProject, MetaDetail $payment)
    {
        return view('dashboard.realEstate.projects.sub.paymentPlans.edit', compact('payment','project', 'subProject'));
    }
    public function updatePayment(ProjectPaymentRequest $request, Project $project,Project $subProject,   MetaDetail $payment)
    {
        try{
            $payment->name = $request->name;
            $payment->value = $request->value;
            $payment->key = $request->key;
            $payment->save();
            return redirect()->route('dashboard.projects.subProjects.paymentPlans', [$project->id, $subProject->id ])->with('success','Payment Plan has been updated successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.projects.subProjects.paymentPlans', [$project->id,  $subProject->id ])->with('error',$error->getMessage());
        }
    }
    public function destroyPayment(Project $project ,Project $subProject, MetaDetail $payment)
    {
        try{
            $payment->delete();
            return redirect()->route('dashboard.projects.subProjects.paymentPlans',[$project->id,  $subProject->id])->with('success','Payment Plan has been deleted successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.projects.subProjects.paymentPlans',[$project->id, $subProject->id])->with('error',$error->getMessage());
        }
    }
    public function floorplansDestroy(Project $project, Project $subProject)
    {
        try{
           
            $subProject->clearMediaCollection('floorplans');
            return redirect()->route('dashboard.projects.subProjects.edit', [$project->id, $subProject->id])->with('success','Floor Plan has been deleted successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.projects.subProjects.edit', [$project->id, $subProject->id])->with('error',$error->getMessage());
        }
    }
    
    public function floorplanDestroy(Project $project, Project $subProject, $floorplan)
    {
        try{
            $subProject->deleteMedia($floorplan);
            return redirect()->route('dashboard.projects.subProjects.edit', [$project->id, $subProject->id])->with('success','Floor Plan has been deleted successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.projects.subProjects.edit', [$project->id, $subProject->id])->with('error',$error->getMessage());
        }
    }
}
