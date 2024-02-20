<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DefaultState;

class DefaultStatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stats = DefaultState::latest()->get();

        return view('dashboard.defaultStat.index', compact('stats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.defaultStat.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $data = new DefaultState;
            $data->key = $request->key;
            $data->value = $request->value;
            $data->save();
            return redirect()->route('dashboard.defaultStats.index')->with('success','Data has been created successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.defaultStats.index')->with('error',$error->getMessage());
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
    public function edit(DefaultState $defaultStat)
    {
        return view('dashboard.defaultStat.edit', compact('defaultStat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DefaultState $defaultStat)
    {
        try{
            $defaultStat->key = $request->key;
            $defaultStat->value = $request->value;
            $defaultStat->save();
            return redirect()->route('dashboard.defaultStats.index')->with('success','Data has been created successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.defaultStats.index')->with('error',$error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DefaultState $defaultStat)
    {
        try{
            $defaultStat->delete();
            return redirect()->route('dashboard.defaultStats.index')->with('success','Data has been deleted successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.defaultStats.index')->with('error',$error->getMessage());
        }
    }
}
