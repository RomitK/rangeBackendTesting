<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\SubcommunityRequest;
use App\Models\Community;
use App\Models\Subcommunity;
use Auth;

class SubCommunityController extends Controller
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
    public function index(Request $request)
    {
        $subCommunities = Subcommunity::with('user')
                        ->applyFilters($request->only(['status']))
                        ->orderBy('id','desc')
                        ->get();
        return view('dashboard.realEstate.subCommunities.index', compact('subCommunities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $communities = Community::active()->orderBy('id','desc')->get();
        return view('dashboard.realEstate.subCommunities.create',compact('communities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubcommunityRequest $request)
    {
        try{
            $subcommunity = new Subcommunity;
            $subcommunity->name = $request->name;
            $subcommunity->community_id = $request->community_id;
            $subcommunity->status = $request->status;
            $subcommunity->user_id = Auth::user()->id;

            $subcommunity->save();
            return redirect()->route('dashboard.subCommunities.index')->with('success','Sub Community has been created successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.subCommunities.index')->with('error',$error->getMessage());
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
    public function edit($id)
    {

        $subcommunity = Subcommunity::where('id',$id)->first();
        $communities = Community::active()->orderBy('id','desc')->get();
        return view('dashboard.realEstate.subCommunities.edit',compact('subcommunity','communities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubcommunityRequest $request, $id)
    {
        $subcommunity = Subcommunity::where('id',$id)->first();
        try{
            $subcommunity->name = $request->name;
            $subcommunity->status = $request->status;
            $subcommunity->community_id = $request->community_id;
            $subcommunity->user_id = Auth::user()->id;
            $subcommunity->save();

            return redirect()->route('dashboard.subCommunities.index')->with('success','Sub Community has been updated successfully');
        }catch(\Exception $error){
            return redirect()->route('dashboard.subCommunities.index')->with('error',$error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Subcommunity::find($id)->delete();

            return redirect()->route('dashboard.subCommunities.index')->with('success','Sub Community has been deleted successfully');
        }catch(\Exception $error){
            return redirect()->route('dashboard.subCommunities.index')->with('error',$error->getMessage());
        }

    }
}
