<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\LanguageRequest;
use App\Models\Language;
use Auth;


class LanguageController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.languages'),
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
        $languages = Language::with('user')->latest()->get();

        return view('dashboard.languages.index', compact('languages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.languages.create');
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
            $language = new Language;
            $language->name = $request->name;
            $language->status = $request->status;
            $language->user_id = Auth::user()->id;
            $language->save();
            return redirect()->route('dashboard.languages.index')->with('success','Language has been created successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.languages.index')->with('error',$error->getMessage());
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
    public function edit(Language $language)
    {
        return view('dashboard.languages.edit', compact('language'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Language $language)
    {
        try{
            $language->name = $request->name;
            $language->status = $request->status;
            $language->save();
            return redirect()->route('dashboard.languages.index')->with('success','Language has been updated successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.languages.index')->with('error',$error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Language $language)
    {
        try{
            $language->delete();
            return redirect()->route('dashboard.languages.index')->with('success','Language has been deleted successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.languages.index')->with('error',$error->getMessage());
        }
    }
}
