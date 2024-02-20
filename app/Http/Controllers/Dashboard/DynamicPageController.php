<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\DynamicPageRequest;
use Illuminate\Support\Str;
use App\Models\{DynamicPage};
use Auth;
use DB;

class DynamicPageController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.languages'),
        ['only' => ['index','create', 'edit','show', 'update', 'destroy']
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = DynamicPage::with('user')->latest()->get();

        return view('dashboard.pageContents.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.pageContents.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DynamicPageRequest $request)
    {
        try{
            $page = new DynamicPage;
            $page->page_name = $request->page_name;
            $page->slug = $request->slug;
            $page->status = $request->status;
            $page->user_id = Auth::user()->id;
            $page->description = $request->description;
            $page->meta_title = $request->meta_title;
            $page->meta_description = $request->meta_description;
            $page->meta_keywords = $request->meta_keywords;
            $page->save();
            
            return redirect()->route('dashboard.dynamicPages.index')->with('success','Page has been created successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.dynamicPages.index')->with('error',$error->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DynamicPage $dynamicPage)
    {
        return redirect()->route('dynamicPage', $dynamicPage->slug);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(DynamicPage $dynamicPage)
    {
        return view('dashboard.pageContents.pages.edit', compact('dynamicPage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DynamicPage $dynamicPage)
    {
        try{
            $dynamicPage->page_name = $request->page_name;
            $dynamicPage->slug = $request->slug;
            $dynamicPage->status = $request->status;
            $dynamicPage->description = $request->description;
            $dynamicPage->meta_title = $request->meta_title;
            $dynamicPage->meta_description = $request->meta_description;
            $dynamicPage->meta_keywords = $request->meta_keywords;
            $dynamicPage->save();
            
            return redirect()->route('dashboard.dynamicPages.index')->with('success','Page has been updated successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.dynamicPages.index')->with('error',$error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DynamicPage $dynamicPage)
    {
        try{
            $dynamicPage->delete();
          
            return redirect()->route('dashboard.dynamicPages.index')->with('success','Page has been deleted successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.dynamicPages.index')->with('error',$error->getMessage());
        }
    }
}
