<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\VideoGallery as DashboardVideoGallery;
use App\Http\Requests\Dashboard\VideoGalleryRequest;
use App\Models\VideoGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
class VideoGalleryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.content_management'),
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
        $videoGallery = VideoGallery::with('user')
        ->applyFilters($request->only(['status']))
        ->orderBy('id','desc')
        ->get();

        return view('dashboard.contentManagement.videoGallery.index', compact('videoGallery'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.contentManagement.videoGallery.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VideoGalleryRequest $request)
    {
        try{
            $videoGallery = new VideoGallery;
            $videoGallery->title = $request->title;
            $videoGallery->status = $request->status;
            $videoGallery->user_id = Auth::user()->id;
            if ($request->hasFile('video')) {
                $img =  $request->file('video');
                $imgExt = $img->getClientOriginalExtension();

                $imageName =  Str::slug($request->title).'.'.$imgExt;
                $videoGallery->addMediaFromRequest('video')->usingFileName($imageName)->toMediaCollection('videos', 'articleFiles');

            }
            if ($request->hasFile('mainImage')) {
                $img =  $request->file('mainImage');
                $imgExt = $img->getClientOriginalExtension();

                $imageName =  Str::slug($request->title).'.'.$imgExt;
                $videoGallery->addMediaFromRequest('mainImage')->usingFileName($imageName)->toMediaCollection('mainImages', 'articleFiles');

            }
            $videoGallery->save();
            return response()->json([
                'success' => true,
                'message'=> 'Video Gallery has been created successfully.',
                'redirect' => route('dashboard.video-gallery.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.video-gallery.index'),
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
    public function edit(VideoGallery $videoGallery)
    {
        return view('dashboard.contentManagement.videoGallery.edit',compact('videoGallery'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VideoGalleryRequest $request, VideoGallery $videoGallery)
    {
        try{
            $videoGallery->title = $request->title;
            $videoGallery->status = $request->status;
            $videoGallery->user_id = Auth::user()->id;
            if ($request->hasFile('video')) {
                $videoGallery->clearMediaCollection('videos');
                $img =  $request->file('video');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title).'.'.$imgExt;
                $videoGallery->addMediaFromRequest('video')->usingFileName($imageName)->toMediaCollection('videos', 'articleFiles');

            }
            if ($request->hasFile('mainImage')) {
                $videoGallery->clearMediaCollection('mainImages');
                $img =  $request->file('mainImage');
                $imgExt = $img->getClientOriginalExtension();

                $imageName =  Str::slug($request->title).'.'.$imgExt;
                $videoGallery->addMediaFromRequest('mainImage')->usingFileName($imageName)->toMediaCollection('mainImages', 'articleFiles');

            }
            $videoGallery->save();

            return response()->json([
                'success' => true,
                'message'=> 'Video Gallery has been updated successfully.',
                'redirect' => route('dashboard.video-gallery.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.video-gallery.index'),
            ]);
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
            VideoGallery::find($id)->delete();

            return redirect()->route('dashboard.video-gallery.index')->with('success','Video Gallery has been deleted successfully');
        }catch(\Exception $error){
            return redirect()->route('dashboard.video-gallery.index')->with('error',$error->getMessage());
        }


    }

}
