<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Project\{
    ProjectBedroomRequest,
    ProjectBedroomSpecificationRequest
};
use Illuminate\Support\Str;
use App\Models\{
    Project,
    ProjectBedroom,
    MetaDetail
};
use DB;
use Auth;
class ProjectBedroomController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.offplan'),
        ['only' => ['index','create', 'edit', 'update', 'destroy',
                    'specifications','createSpecification','storeSpecification','editSpecification','updateSpecification','destroySpecification']
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project, Project $subProject)
    {
        return view('dashboard.realEstate.projects.sub.bedrooms.index', compact('project', 'subProject'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project, Project $subProject)
    {
        return view('dashboard.realEstate.projects.sub.bedrooms.create', compact('project', 'subProject'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectBedroomRequest $request, Project $project, Project $subProject)
    {
        DB::beginTransaction();
        try{
            $bedroom = new ProjectBedroom;
            $bedroom->bedroom_number = $request->bedroom_number;
            $bedroom->bathroom_number = $request->bathroom_number;
            $bedroom->area = $request->area;
            $bedroom->price = $request->price;
            $bedroom->status = $request->status;
            $bedroom->project_id = $subProject->id;
            $bedroom->user_id = Auth::user()->id;

            if ($request->hasFile('floorplan_image')) {
                $image =  $request->file('floorplan_image');
                $ext = $image->getClientOriginalExtension();
                $imageName =  Str::slug($subProject->title).'_floorplan_image'.$ext;
                $bedroom->addMediaFromRequest('floorplan_image')->usingFileName($imageName)->toMediaCollection('floorPlanImages', 'projectFiles');
            }

            if ($request->hasFile('floorplan_file')) {
                $file =  $request->file('floorplan_file');
                $ext = $file->getClientOriginalExtension();
                $fileName =  Str::slug($subProject->title).'_floorplan_file'.$ext;
                $bedroom->addMediaFromRequest('floorplan_file')->usingFileName($fileName)->toMediaCollection('floorPlanFiles', 'projectFiles');
            }
            $bedroom->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message'=> 'Bedroom has been created successfully.',
                'redirect' => route('dashboard.projects.subProjects.bedrooms',[$project->id, $subProject->id]),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.projects.subProjects.bedrooms',[$project->id, $subProject->id]),
            ]);
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, Project $subProject, ProjectBedroom $bedroom)
    {
        return view('dashboard.realEstate.projects.sub.bedrooms.edit', compact('project', 'subProject', 'bedroom'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectBedroomRequest $request, Project $project, Project $subProject, ProjectBedroom $bedroom)
    {
        DB::beginTransaction();
        try{
            $bedroom->bedroom_number = $request->bedroom_number;
            $bedroom->bathroom_number = $request->bathroom_number;
            $bedroom->area = $request->area;
            $bedroom->price = $request->price;
            $bedroom->status = $request->status;
            if ($request->hasFile('floorplan_image')) {
                $bedroom->clearMediaCollection('floorPlanImages');
                $image =  $request->file('floorplan_image');
                $ext = $image->getClientOriginalExtension();
                $imageName =  Str::slug($subProject->title).'_floorplan_image'.$ext;
                $bedroom->addMediaFromRequest('floorplan_image')->usingFileName($imageName)->toMediaCollection('floorPlanImages', 'projectFiles');
            }

            if ($request->hasFile('floorplan_file')) {
                $bedroom->clearMediaCollection('floorPlanFiles');
                $file =  $request->file('floorplan_file');
                $ext = $file->getClientOriginalExtension();
                $fileName =  Str::slug($subProject->title).'_floorplan_file'.$ext;
                $bedroom->addMediaFromRequest('floorplan_file')->usingFileName($fileName)->toMediaCollection('floorPlanFiles', 'projectFiles');
            }
            $bedroom->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message'=> 'Bedroom has been updated successfully.',
                'redirect' => route('dashboard.projects.subProjects.bedrooms',[$project->id, $subProject->id]),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.projects.subProjects.bedrooms',[$project->id, $subProject->id]),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, Project $subProject, ProjectBedroom $bedroom)
    {
        try{
            $bedroom->details()->delete();
            $bedroom->delete();
            return redirect()->route('dashboard.projects.subProjects.bedrooms',[$project->id, $subProject->id] )->with('success','Project Bedroom has been deleted successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.projects.subProjects.bedrooms')->with('error',$error->getMessage());
        }
    }

    public function specifications(Project $project, Project $subProject, ProjectBedroom $bedroom)
    {
        return view('dashboard.realEstate.projects.sub.bedrooms.specifications.index', compact('project', 'subProject', 'bedroom'));
    }
    public function createSpecification(Project $project, Project $subProject, ProjectBedroom $bedroom)
    {
        return view('dashboard.realEstate.projects.sub.bedrooms.specifications.create', compact('project', 'subProject', 'bedroom'));
    }
    public function storeSpecification(ProjectBedroomSpecificationRequest $request, Project $project, Project $subProject, ProjectBedroom $bedroom)
    {
        DB::beginTransaction();
        try{
            $specification = $bedroom->details()->create(['name'=>$request->name, 'value'=>$request->value]);

            if ($request->hasFile('icon')) {
                $image =  $request->file('icon');
                $ext = $image->getClientOriginalExtension();
                $imageName =  Str::slug($request->name).'_icon'.$ext;
                $specification->addMediaFromRequest('icon')->usingFileName($imageName)->toMediaCollection('icons', 'projectFiles');
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message'=> 'Bedroom Specification has been created successfully.',
                'redirect' => route('dashboard.projects.subProjects.bedrooms.specifications',[$project->id, $subProject->id, $bedroom->id]),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.projects.subProjects.bedrooms.specifications',[$project->id, $subProject->id, $bedroom->id]),
            ]);
        }
    }
    public function editSpecification(Project $project, Project $subProject, ProjectBedroom $bedroom, MetaDetail $specification)
    {
        return view('dashboard.realEstate.projects.sub.bedrooms.specifications.edit', compact('project', 'subProject', 'bedroom', 'specification'));
    }
    public function updateSpecification(ProjectBedroomSpecificationRequest $request, Project $project, Project $subProject, ProjectBedroom $bedroom, MetaDetail $specification)
    {
        DB::beginTransaction();
        try{
            $specification->name =$request->name;
            $specification->value=$request->value;
            if ($request->hasFile('icon')) {
                $specification->clearMediaCollection('icons');
                $image =  $request->file('icon');
                $ext = $image->getClientOriginalExtension();
                $imageName =  Str::slug($request->name).'_icon'.$ext;
                $specification->addMediaFromRequest('icon')->usingFileName($imageName)->toMediaCollection('icons', 'projectFiles');
            }
            $specification->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message'=> 'Bedroom Specification has been updated successfully.',
                'redirect' => route('dashboard.projects.subProjects.bedrooms.specifications',[$project->id, $subProject->id, $bedroom->id]),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.projects.subProjects.bedrooms.specifications',[$project->id, $subProject->id, $bedroom->id]),
            ]);
        }
    }
    public function destroySpecification(Project $project, Project $subProject, ProjectBedroom $bedroom, MetaDetail $specification)
    {
        try{
            $specification->delete();
            return redirect()->route('dashboard.projects.subProjects.bedrooms.specifications',[$project->id, $subProject->id, $bedroom->id])->with('success','Project Bedroom has been deleted successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.projects.subProjects.bedrooms.specifications',[$project->id, $subProject->id, $bedroom->id])->with('error',$error->getMessage());
        }
    }
}
