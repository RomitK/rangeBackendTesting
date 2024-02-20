<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\PageTagRequest;
use App\Models\PageTag;
use Illuminate\Support\Str;
use Auth;

class PageTagController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.tags'),
        ['only' => ['index','create', 'edit', 'update', 'destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tags = PageTag::with('user')
        ->applyFilters($request->only(['status']))
        ->latest()
        ->get();
        return view('dashboard.seo.pageTags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $routes = getFrontentRouteInfo();

        return view('dashboard.seo.pageTags.create', compact('routes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PageTagRequest $request)
    {
        try{
            $page_tag = new PageTag;
            $page_tag->page_name = $request->page_name;
            $page_tag->meta_title = $request->meta_title;
            $page_tag->meta_keywords = $request->meta_keywords;
            $page_tag->meta_description = $request->meta_description;
            $page_tag->status = $request->status;
            $page_tag->user_id = Auth::user()->id;

            if ($request->hasFile('banner_image')) {
                $img =  $request->file('banner_image');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->page_name).'.'.$imgExt;
                $page_tag->addMediaFromRequest('banner_image')->usingFileName($imageName)->toMediaCollection('banners', 'bannerFiles');
            }
            $page_tag->save();
            return redirect()->route('dashboard.page-tags.index')->with('success','Page Tag has been created successfully.');

        }catch(\Exception $error){
            return redirect()->route('dashboard.page-tags.index')->with('error',$error->getMessage());
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
    public function edit(PageTag $page_tag)
    {
        $routes = getFrontentRouteInfo();
        return view('dashboard.seo.pageTags.edit', compact('page_tag', 'routes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PageTagRequest $request, PageTag $page_tag)
    {
        try{
            $page_tag->page_name = $request->page_name;
            $page_tag->meta_title = $request->meta_title;
            $page_tag->meta_keywords = $request->meta_keywords;
            $page_tag->meta_description = $request->meta_description;
            $page_tag->status = $request->status;
            $page_tag->user_id = Auth::user()->id;
            if ($request->hasFile('banner_image')) {

                $page_tag->clearMediaCollection('banners');
                $img =  $request->file('banner_image');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->page_name).'.'.$imgExt;
                $page_tag->addMediaFromRequest('banner_image')->usingFileName($imageName)->toMediaCollection('banners', 'bannerFiles');
            }
            $page_tag->save();
            return redirect()->route('dashboard.page-tags.index')->with('success','Page Tag has been updated successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.page-tags.index')->with('error',$error->getMessage());
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
            PageTag::find($id)->delete();

            return redirect()->route('dashboard.page-tags.index')->with('success','Page Tag has been deleted successfully');
        }catch(\Exception $error){
            return redirect()->route('dashboard.page-tags.index')->with('error',$error->getMessage());
        }

    }
}
