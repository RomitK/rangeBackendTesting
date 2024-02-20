<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Project\{
    ProjectStatRequest,
    ProjectStatValueRequest
};
use App\Models\{
    Project,
    Stat,
    StatData
};

class ProjectStatController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.offplan'),
        ['only' => ['index','create', 'edit', 'update', 'destroy',
                    'values','createValue','storeValue','editValue','updateValue','destroyValue']
        ]);
    }
    public function index(Project $project)
    {
        return view('dashboard.realEstate.projects.stats.index', compact('project'));
    }
    public function create(Project $project)
    {
        return view('dashboard.realEstate.projects.stats.create', compact('project'));
    }
    public function store(ProjectStatRequest $request, Project $project)
    {
        try{
            $stat = new Stat;
            $stat->name = $request->name;
            $project->stats()->save($stat);
            return redirect()->route('dashboard.projects.stats',$project->id )->with('success','Project Stat has been created successfully.');
        }catch(\Exception $error){
            return redirect()->back()->with('error',$error->getMessage());
        }
    }
    public function edit(Project $project, Stat $stat)
    {
        return view('dashboard.realEstate.projects.stats.edit', compact('project', 'stat'));
    }
    public function update(ProjectStatRequest $request, Project $project,  Stat $stat)
    {
        try{
            $stat->name = $request->name;
            $project->stats()->save($stat);
            return redirect()->route('dashboard.projects.stats',$project->id )->with('success','Project Stat has been created successfully.');
        }catch(\Exception $error){
            return redirect()->back()->with('error',$error->getMessage());
        }
    }
    public function destroy(Project $project , Stat $stat)
    {
        try{
            $stat->delete();
            return redirect()->route('dashboard.projects.stats',$project->id )->with('success','Project Stat has been deleted successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.projects.stats',$project->id)->with('error',$error->getMessage());
        }
    }
    public function values(Project $project , Stat $stat)
    {
        return view('dashboard.realEstate.projects.stats.values.index', compact('project', 'stat'));
    }
    public function createValue(Project $project,  Stat $stat)
    {
        return view('dashboard.realEstate.projects.stats.values.create', compact('project', 'stat'));
    }
    public function storeValue(ProjectStatValueRequest $request, Project $project,  Stat $stat)
    {
        try{
            $stat->values()->create(['key'=>$request->key, 'value'=>$request->value]);
            return redirect()->route('dashboard.projects.stats.statData',[$project->id, $stat->id] )->with('success','Project Stat Value has been created successfully.');
        }catch(\Exception $error){
            return redirect()->back()->with('error',$error->getMessage());
        }
    }
    public function editValue(Project $project, Stat $stat, StatData $statData)
    {

        return view('dashboard.realEstate.projects.stats.values.edit', compact('project', 'stat', 'statData'));
    }
    public function updateValue(ProjectStatValueRequest $request, Project $project,  Stat $stat, StatData $statData)
    {
        try{
            $statData->key = $request->key;
            $statData->value = $request->value;
            $statData->save();
            return redirect()->route('dashboard.projects.stats.statData',[$project->id, $stat->id])->with('success','Project Stat Value has been updated successfully.');
        }catch(\Exception $error){
            return redirect()->back()->with('error',$error->getMessage());
        }
    }
    public function destroyValue(Project $project , Stat $stat, StatData $statData)
    {
        try{
            $statData->delete();
            return redirect()->route('dashboard.projects.stats.statData',[$project->id, $stat->id])->with('success','Project Stat Value has been deleted successfully.');
        }catch(\Exception $error){
            return redirect()->back()->with('error',$error->getMessage());
        }
    }

}
