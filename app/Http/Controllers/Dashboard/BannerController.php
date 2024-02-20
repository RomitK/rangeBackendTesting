<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\BannerRequest;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DB;

class BannerController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.page_contents'),
        ['only' => ['index','create', 'edit', 'update', 'destroy', ]
        ]);
    }
    public function index()
    {
        return redirect()->route(config('constants.home.route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.pageContents.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerRequest $request)
    {
        try{
            $banner = new Banner;
            $banner->page_name = config('constants.Home');
            $banner->status = $request->status;
            $banner->title = $request->title;
            $banner->button_text = $request->button_text;
            $banner->button_link = $request->button_link;
            $banner->user_id = Auth::user()->id;

            if ($request->hasFile('image')) {
                $img =  $request->file('image');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title).'.'.$ext;
                $banner->addMediaFromRequest('image')->usingFileName($imageName)->toMediaCollection('images', 'bannerFiles');
            }

            if ($request->hasFile('video')) {
                $img =  $request->file('video');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title).'.'.$imgExt;
                $banner->addMediaFromRequest('video')->usingFileName($imageName)->toMediaCollection('videos', 'bannerFiles');

            }
            $banner->save();

            return response()->json([
                'success' => true,
                'message'=> 'Banner has been created successfully.',
                'redirect' => route(config('constants.home.route')),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route(config('constants.home.route')),
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
    public function edit(Banner $banner)
    {
        return view('dashboard.pageContents.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BannerRequest $request, Banner $banner)
    {
        try{
            $banner->status = $request->status;
            $banner->title = $request->title;
            $banner->button_text = $request->button_text;
            $banner->button_link = $request->button_link;
            $banner->user_id = Auth::user()->id;
            if ($request->hasFile('image')) {
                $banner->clearMediaCollection('images');
                $img =  $request->file('image');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title).'.'.$ext;
                $banner->addMediaFromRequest('image')->usingFileName($imageName)->toMediaCollection('images', 'bannerFiles');
            }
            if ($request->hasFile('video')) {
                $img =  $request->file('video');
                $banner->clearMediaCollection('videos');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title).'.'.$imgExt;
                $banner->addMediaFromRequest('video')->usingFileName($imageName)->toMediaCollection('videos', 'bannerFiles');

            }
            $banner->save();
            return response()->json([
                'success' => true,
                'message'=> 'Banner has been updated successfully.',
                'redirect' => route(config('constants.home.route')),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route(config('constants.home.route')),
            ]);
        }     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Banner $banner)
    {
        try{
            $banner->delete();
            return redirect()->route(config('constants.home.route'))->with('success','Banner has been deleted successfully');
        }catch(\Exception $error){
            return redirect()->route(config('constants.home.route'))->with('error',$error->getMessage());
        }
    }
}
