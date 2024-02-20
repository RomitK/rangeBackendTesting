<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\Dashboard\FloorPlanRequest;
use App\Models\{
    SubFloorPlan,
    Accommodation,
    FloorPlan,
    Project,
    Community};
use Auth;
use DB;
class FloorPlanController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.real_estate'),
        ['only' => ['index','create', 'edit', 'update', 'destroy', 'destroySubFloor']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $floorPlans = FloorPlan::with('user')->latest()->get();

        return view('dashboard.realEstate.floorPlans.index', compact('floorPlans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::active()->latest()->get();
        $communities = Community::active()->latest()->get();
        $accommodations = Accommodation::active()->latest()->get();

        return view('dashboard.realEstate.floorPlans.create', compact('accommodations','projects', 'communities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FloorPlanRequest $request)
    {
        DB::beginTransaction();
        try{
            $floorPlan = new FloorPlan;
            $floorPlan->title = $request->title;
            $floorPlan->status = $request->status;
            $floorPlan->user_id = Auth::user()->id;
            $floorPlan->meta_title = $request->meta_title;
            $floorPlan->meta_description = $request->meta_description;
            $floorPlan->meta_keywords = $request->meta_keywords;

            if($request->has('project_name')){
                $floorPlan->project_name = $request->project_name;
                if(Project::where('title', $request->project_name)->exists()){
                    $floorPlan->project_id = Project::where('title', $request->project_name)->first()->id;
                }
            }
            if ($request->hasFile('floorPlanFile')) {
                $file =  $request->file('floorPlanFile');
                $ext = $file->getClientOriginalExtension();
                $fileName =  Str::slug($request->project_name)."_floorplan.".$ext;
                $floorPlan->addMediaFromRequest('floorPlanFile')->usingFileName($fileName)->toMediaCollection('files', 'floorPlanFiles');
            }

            if ($request->hasFile('mainImage')) {
                $img =  $request->file('mainImage');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title).'_image.'.$ext;
                $floorPlan->addMediaFromRequest('mainImage')->usingFileName($imageName)->toMediaCollection('images', 'floorPlanFiles');
            }
            if($request->has('community_id')){
                $floorPlan->community()->associate($request->community_id);
            }
            if($request->has('sub_community_id')){
                $floorPlan->subCommunity()->associate($request->sub_community_id);
            }
            
            $floorPlan->save();
            if($request->names){
                foreach($request->names as $key=>$name)
                {
                    if($request->names[$key] != null || $request->areas[$key] != null || isset($request->images[$key])){
                        $subFloorPlan = new SubFloorPlan;
                        $subFloorPlan->name = $request->names[$key];
                        $subFloorPlan->area = $request->areas[$key];
                        $subFloorPlan->accommodation_id = $request->accommodationIds[$key];
                        $subFloorPlan->floor_plan_id = $floorPlan->id;
                        if(isset($request->images[$key])) {
                            $image = $request->file('images')[$key];
                            $ext = $image->getClientOriginalExtension();
                            $imageName =  Str::slug($request->names[$key]).'_image.'.$ext;
                            $subFloorPlan->addMedia($image)->usingFileName($imageName)->toMediaCollection('images', 'floorPlanFiles');
                        }
                        $subFloorPlan->save();
                    }


                }
            }


            DB::commit();

            return response()->json([
                'success' => true,
                'message'=> 'Floor Plan has been created successfully.',
                'redirect' => route('dashboard.floorPlans.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.floorPlans.index'),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FloorPlan $floorPlan)
    {
        $projects = Project::active()->latest()->pluck('title');
        $projectNames = $projects->contains($floorPlan->title);
        if(!$projects->contains($floorPlan->title)){
            $projects->prepend($floorPlan->project_name);
        }
        $communities = Community::active()->latest()->get();
        $accommodations = Accommodation::active()->latest()->get();

        return view('dashboard.realEstate.floorPlans.edit', compact('accommodations','projects', 'communities', 'floorPlan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FloorPlanRequest $request, FloorPlan $floorPlan)
    {
        DB::beginTransaction();
        try{
            $floorPlan->title = $request->title;
            $floorPlan->status = $request->status;
            $floorPlan->meta_title = $request->meta_title;
            $floorPlan->meta_description = $request->meta_description;
            $floorPlan->meta_keywords = $request->meta_keywords;

            if ($request->hasFile('floorPlanFile')) {
                if($floorPlan->floorPlanFile){
                    $floorPlan->clearMediaCollection('files');
                }
                $file =  $request->file('floorPlanFile');
                $ext = $file->getClientOriginalExtension();
                $fileName =  Str::slug($request->project_name)."_floorplan.".$ext;

                $floorPlan->addMediaFromRequest('floorPlanFile')->usingFileName($fileName)->toMediaCollection('files', 'floorPlanFiles');
            }

            if ($request->hasFile('mainImage')) {
                $floorPlan->clearMediaCollection('images');
                $img =  $request->file('mainImage');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title).'_image.'.$ext;
                $floorPlan->addMediaFromRequest('mainImage')->usingFileName($imageName)->toMediaCollection('images', 'floorPlanFiles');
            }
            if($request->has('community_id')){
                $floorPlan->community()->associate($request->community_id);
            }
            if($request->has('sub_community_id')){
                $floorPlan->subCommunity()->associate($request->sub_community_id);
            }
            if($request->has('project_name')){
                $floorPlan->project_name = $request->project_name;
                if(Project::where('title', $request->project_name)->exists()){
                    $floorPlan->project_id = Project::where('title', $request->project_name)->first()->id;
                }
            }
            $floorPlan->save();

            if($request->names){
                foreach($request->names as $key=>$name)
                {
                    if($request->ids != NULL && array_key_exists($key,$request->ids) &&  SubFloorPlan::find($request->ids[$key])){
                        $subFloorPlan = SubFloorPlan::find($request->ids[$key]);
                        $subFloorPlan->name = $request->names[$key];
                        $subFloorPlan->area = $request->areas[$key];
                        $subFloorPlan->accommodation_id = $request->accommodationIds[$key];
                        if(isset($request->images[$key])) {
                            $image = $request->file('images')[$key];
                            if(isset($request->images[$key])){
                                $subFloorPlan->clearMediaCollection('images');
                            }
                            $ext = $image->getClientOriginalExtension();
                            $imageName =  Str::slug($request->names[$key]).'_image.'.$ext;
                            $subFloorPlan->addMedia($image)->usingFileName($imageName)->toMediaCollection('images', 'floorPlanFiles');
                        }
                        $subFloorPlan->save();
                    } elseif( $request->names[$key] != null || $request->areas[$key] || isset($request->images[$key])){
                        $subFloorPlan = new SubFloorPlan;
                        $subFloorPlan->floor_plan_id = $floorPlan->id;
                        $subFloorPlan->name = $request->names[$key];
                        $subFloorPlan->area = $request->areas[$key];
                        $subFloorPlan->accommodation_id = $request->accommodationIds[$key];
                        if(isset($request->images[$key])) {
                            $image = $request->file('images')[$key];
                            $ext = $image->getClientOriginalExtension();
                            $imageName =  Str::slug($request->names[$key]).'_image.'.$ext;
                            $subFloorPlan->addMedia($image)->usingFileName($imageName)->toMediaCollection('images', 'floorPlanFiles');
                        }
                        $subFloorPlan->save();
                    }

                }
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message'=> 'Floor Plan has been updated successfully.',
                'redirect' => route('dashboard.floorPlans.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.floorPlans.index'),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FloorPlan $floorPlan)
    {
        try{
            $floorPlan->subFloorPlans()->delete();
            $floorPlan->delete();
            return redirect()->route('dashboard.floorPlans.index')->with('success','Floor Plan has been deleted successfully');
        }catch(\Exception $error){
            return redirect()->route('dashboard.floorPlans.index')->with('error',$error->getMessage());
        }
    }
    public function destroySubFloor(FloorPlan $floorPlan, SubFloorPlan $subFloorPlan)
    {
        try{
            $subFloorPlan->delete();
            return redirect()->route('dashboard.floorPlans.edit', $floorPlan->id)->with('success','Sub Floor Plan has been deleted successfully');
        }catch(\Exception $error){
            return redirect()->route('dashboard.floorPlans.edit', $floorPlan->id)->with('error',$error->getMessage());
        }
    }
}
