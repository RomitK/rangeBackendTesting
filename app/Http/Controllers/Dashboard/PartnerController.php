<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\PartnerRequest;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DB;

class PartnerController extends Controller
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
        return redirect()->route(config('constants.about.route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.pageContents.partners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PartnerRequest $request)
    {
        try{
            $partner = new Partner;
            $partner->status = $request->status;
            $partner->name = $request->name;
            $partner->less_description = $request->less_description;
            $partner->more_description = $request->more_description;
            $partner->video_iframe = $request->video_iframe;
            $partner->user_id = Auth::user()->id;

            if ($request->hasFile('image')) {
                $img =  $request->file('image');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name).'_image.'.$ext;
                $partner->addMediaFromRequest('image')->usingFileName($imageName)->toMediaCollection('images', 'partnerFiles');
            }

            if ($request->hasFile('video')) {
                $img =  $request->file('video');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name).'.'.$imgExt;
                $partner->addMediaFromRequest('video')->usingFileName($imageName)->toMediaCollection('videos', 'partnerFiles');

            }
            $partner->save();
            return response()->json([
                'success' => true,
                'message'=> 'Partner has been created successfully.',
                'redirect' => route(config('constants.about.route')),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route(config('constants.about.route')),
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
    public function edit(Partner $partner)
    {
        return view('dashboard.pageContents.partners.edit', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PartnerRequest $request, Partner $partner)
    {
        try{
            $partner->status = $request->status;
            $partner->name = $request->name;
            $partner->less_description = $request->less_description;
            $partner->more_description = $request->more_description;
            $partner->video_iframe = $request->video_iframe;
            $partner->user_id = Auth::user()->id;

            if ($request->hasFile('image')) {
                $partner->clearMediaCollection('images');
                $img =  $request->file('image');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name).'_image.'.$ext;
                $partner->addMediaFromRequest('image')->usingFileName($imageName)->toMediaCollection('images', 'partnerFiles');
            }

            if ($request->hasFile('video')) {
                $partner->clearMediaCollection('videos');
                $img =  $request->file('video');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name).'.'.$imgExt;
                $partner->addMediaFromRequest('video')->usingFileName($imageName)->toMediaCollection('videos', 'partnerFiles');

            }
            $partner->save();
           return response()->json([
                'success' => true,
                'message'=> 'Partner has been updated successfully.',
                'redirect' => route(config('constants.about.route')),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route(config('constants.about.route')),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Partner $partner)
    {
        try{
            $partner->delete();
            return redirect()->route(config('constants.about.route'))->with('success','Banner has been deleted successfully');
        }catch(\Exception $error){
            return redirect()->route(config('constants.about.route'))->with('error',$error->getMessage());
        }
    }
}
