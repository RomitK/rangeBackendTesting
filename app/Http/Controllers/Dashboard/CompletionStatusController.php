<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\CompletionStatusRequest;
use App\Models\CompletionStatus;
use Auth;

class CompletionStatusController extends Controller
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
        $statuses = CompletionStatus::with('user')
        ->applyFilters($request->only(['status']))
        ->orderBy('id','desc')
        ->get();
        return view('dashboard.realEstate.completionStatuses.index', compact('statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.realEstate.completionStatuses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompletionStatusRequest $request)
    {
        try{
            $completionStatus = new CompletionStatus;
            $completionStatus->name = $request->name;
            $completionStatus->status = $request->status;
            $completionStatus->user_id = Auth::user()->id;
            $completionStatus->save();
            return redirect()->route('dashboard.completion-statuses.index')->with('success','Completion Status has been created successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.completion-statuses.index')->with('error',$error->getMessage());
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
    public function edit(CompletionStatus $completionStatus)
    {
        return view('dashboard.realEstate.completionStatuses.edit',compact('completionStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CompletionStatusRequest $request, CompletionStatus $completionStatus)
    {
        try{
            $completionStatus->name = $request->name;
            $completionStatus->status = $request->status;
            $completionStatus->save();

            return redirect()->route('dashboard.completion-statuses.index')->with('success','Status has been updated successfully');
        }catch(\Exception $error){
            return redirect()->route('dashboard.completion-statuses.index')->with('error',$error->getMessage());
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
            CompletionStatus::find($id)->delete();

            return redirect()->route('dashboard.completion-statuses.index')->with('success','Status has been deleted successfully');
        }catch(\Exception $error){
            return redirect()->route('dashboard.completion-statuses.index')->with('error',$error->getMessage());
        }

    }
}
