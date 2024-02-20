<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Community,
    Stat,
    StatData,
    DefaultState
};

class CommunityStatController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.real_estate'),
        ['only' => ['index','create', 'edit', 'update', 'destroy',
                    'values','createValue','storeValue','editValue','updateValue','destroyValue']
        ]);
    }
    public function index(Community $community)
    {
        return view('dashboard.realEstate.communities.stats.index', compact('community'));
    }
    public function create(Community $community)
    {
        return view('dashboard.realEstate.communities.stats.create', compact('community'));
    }
    public function store(Request $request, Community $community)
    {
        try{
            $stat = new Stat;
            $stat->name = $request->name;
            $community->stats()->save($stat);
            return redirect()->route('dashboard.communities.stats',$community->id )->with('success','Community Stat has been created successfully.');
        }catch(\Exception $error){
            return redirect()->back()->with('error',$error->getMessage());
        }
    }
    public function edit(Community $community, Stat $stat)
    {
        return view('dashboard.realEstate.communities.stats.edit', compact('community', 'stat'));
    }
    public function update(Request $request, Community $community,  Stat $stat)
    {
        try{
            $stat->name = $request->name;
            $community->stats()->save($stat);
            return redirect()->route('dashboard.communities.stats',$community->id )->with('success','Community Stat has been created successfully.');
        }catch(\Exception $error){
            return redirect()->back()->with('error',$error->getMessage());
        }
    }
    public function destroy(Community $community , Stat $stat)
    {
        try{
            $stat->delete();
            return redirect()->route('dashboard.communities.stats',$community->id )->with('success','Community Stat has been deleted successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.communities.stats',$community->id)->with('error',$error->getMessage());
        }
    }
    public function values(Community $community , Stat $stat)
    {
        return view('dashboard.realEstate.communities.stats.values.index', compact('community', 'stat'));
    }
    public function createValue(Community $community,  Stat $stat)
    {
        $defaultStat =DefaultState::latest()->get();
        return view('dashboard.realEstate.communities.stats.values.create', compact('community', 'stat', 'defaultStat'));
    }
    public function storeValue(Request $request, Community $community,  Stat $stat)
    {
        try{
            $stat->values()->create(['key'=>$request->key, 'value'=>$request->value]);
            return redirect()->route('dashboard.communities.stats.statData',[$community->id, $stat->id] )->with('success','Community Stat Value has been created successfully.');
        }catch(\Exception $error){
            return redirect()->back()->with('error',$error->getMessage());
        }
    }
    public function editValue(Community $community, Stat $stat, StatData $statData)
    {
        $defaultStat =DefaultState::latest()->get();
        return view('dashboard.realEstate.communities.stats.values.edit', compact('defaultStat','community', 'stat', 'statData'));
    }
    public function updateValue(Request $request, Community $community,  Stat $stat, StatData $statData)
    {
        try{
            $statData->key = $request->key;
            $statData->value = $request->value;
            $statData->save();
            return redirect()->route('dashboard.communities.stats.statData',[$community->id, $stat->id])->with('success','Community Stat Value has been updated successfully.');
        }catch(\Exception $error){
            return redirect()->back()->with('error',$error->getMessage());
        }
    }
    public function destroyValue(Community $community , Stat $stat, StatData $statData)
    {
        try{
            $statData->delete();
            return redirect()->route('dashboard.communities.stats.statData',[$community->id, $stat->id])->with('success','Community Stat Value has been deleted successfully.');
        }catch(\Exception $error){
            return redirect()->back()->with('error',$error->getMessage());
        }
    }

}
