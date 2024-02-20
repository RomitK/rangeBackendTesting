<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\CounterRequest;
use App\Models\{Counter};
use Auth;
use DB;

class CounterController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.page_contents'),
        ['only' => ['index','create', 'edit', 'update', 'destroy', ]
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->route('dashboard.pageContents.home-page');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.pageContents.counters.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CounterRequest $request)
    {
        try{
            $counter = new Counter;
            $counter->page_name = config('constants.career');
            $counter->status = $request->status;
            $counter->key = $request->key;
            $counter->value = $request->value;
            $counter->name = $request->name;
            $counter->user_id = Auth::user()->id;
            $counter->save();
            return redirect()->route('dashboard.pageContents.career-page')->with('success','Counter has been created successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.pageContents.career-page')->with('error',$error->getMessage());
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
    public function edit(Counter $counter)
    {
        return view('dashboard.pageContents.counters.edit', compact('counter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CounterRequest $request, Counter $counter)
    {
        try{
            $counter->status = $request->status;
            $counter->key = $request->key;
            $counter->value = $request->value;
            $counter->name = $request->name;
            $counter->save();
            return redirect()->route('dashboard.pageContents.career-page')->with('success','Counter has been Updated successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.pageContents.career-page')->with('error',$error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Counter $counter)
    {
        try{
            $counter->delete();
            return redirect()->route('dashboard.pageContents.career-page')->with('success','Counter has been deleted successfully');
        }catch(\Exception $error){
            return redirect()->route('dashboard.pageContents.career-page')->with('error',$error->getMessage());
        }
    }
}
