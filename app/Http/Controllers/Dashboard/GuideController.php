<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\GuideRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Str;
use App\Models\{
    Tag,
    TagCategory,
    Guide
};
use Auth;
use DB;

class GuideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($page)
    {
        $tags = TagCategory::active()->guideTag()->orderBy('id', 'desc')->get();
        return view('dashboard.pageContents.guides.create', compact('page', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GuideRequest $request)
    {

        ini_set('upload_max_filesize', '4000M');
        ini_set('post_max_size', '4000M');

        DB::beginTransaction();
        try {
            $guide = new Guide;
            $guide->title = $request->title;
            $guide->status = $request->status;
            $guide->user_id = Auth::user()->id;
            $guide->crm_sub_source_id = $request->crm_sub_source_id;
            $guide->orderBy = $request->orderBy;
            $guide->description = $request->description;

            if ($request->hasFile('sliderImage')) {
                $sliderImage =  $request->file('sliderImage');
                $ext = $sliderImage->getClientOriginalExtension();
                $sliderImage =  Str::slug($request->title) . 'sliderImage.' . $ext;
                $guide->addMediaFromRequest('sliderImage')->usingFileName($sliderImage)->toMediaCollection('sliderImages', 'guideFiles');
            }

            if ($request->hasFile('featureImage')) {
                $featureImage =  $request->file('featureImage');
                $ext = $featureImage->getClientOriginalExtension();
                $featureImage =  Str::slug($request->title) . 'featureImage.' . $ext;
                $guide->addMediaFromRequest('featureImage')->usingFileName($featureImage)->toMediaCollection('featureImages', 'guideFiles');
            }

            if ($request->hasFile('guideFile')) {
                $img =  $request->file('guideFile');
                $ext = $img->getClientOriginalExtension();
                $guideFile =  Str::slug($request->title) . "." . $ext;
                $guide->addMediaFromRequest('guideFile')->usingFileName($guideFile)->toMediaCollection('guides', 'guideFiles');
            }


            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                $guide->is_approved = config('constants.approved');
                $guide->approval_id = Auth::user()->id;
            } else {
                $guide->is_approved = config('constants.requested');
            }

            $guide->updated_by = Auth::user()->id;
            $guide->save();

            $guide->slider_image = $guide->sliderImage;
            $guide->feature_image = $guide->featureImage;
            $guide->guide_file = $guide->guide;
            $guide->save();
            if ($request->has('tagIds')) {
                foreach ($request->tagIds as $tag) {
                    if (TagCategory::where(['id' => $tag, 'type' => config('constants.guide'), 'status' => config('constants.active')])->exists()) {
                        $newtag = TagCategory::where(['id' => $tag, 'type' => config('constants.guide'), 'status' => config('constants.active')])->first();
                        $tag = $newtag->id;
                    } else {
                        $newtag = TagCategory::create(['name' => $tag, 'type' => config('constants.guide'), 'status' => config('constants.active'), 'user_id' => Auth::user()->id]);
                        $tag = $newtag->id;
                    }
                    $guide->tags()->create(['tag_category_id' => $tag]);
                }
            }
            DB::commit();
            return redirect()->route('dashboard.pageContents.dubaiGuide-page')->with('success', 'Guide has been created successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.pageContents.dubaiGuide-page')->with('error', $error->getMessage());
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
    public function edit($page, Guide $guide)
    {
        $tags = TagCategory::active()->guideTag()->orderBy('id', 'desc')->get();
        return view('dashboard.pageContents.guides.edit', compact('page', 'guide', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GuideRequest $request, $page, Guide $guide)
    {
        ini_set('upload_max_filesize', '4000M');
        ini_set('post_max_size', '4000M');

        DB::beginTransaction();
        try {

            $guide->title = $request->title;
            $guide->status = $request->status;
            $guide->orderBy = $request->orderBy;
            $guide->crm_sub_source_id = $request->crm_sub_source_id;
            $guide->description = $request->description;

            if ($request->hasFile('sliderImage')) {
                $guide->clearMediaCollection('sliderImages');
                $sliderImage =  $request->file('sliderImage');
                $ext = $sliderImage->getClientOriginalExtension();
                $sliderImage =  Str::slug($request->title) . 'sliderImage.' . $ext;
                $guide->addMediaFromRequest('sliderImage')->usingFileName($sliderImage)->toMediaCollection('sliderImages', 'guideFiles');
            }

            if ($request->hasFile('featureImage')) {
                $guide->clearMediaCollection('featureImages');
                $featureImage =  $request->file('featureImage');
                $ext = $featureImage->getClientOriginalExtension();
                $featureImage =  Str::slug($request->title) . 'featureImage.' . $ext;
                $guide->addMediaFromRequest('featureImage')->usingFileName($featureImage)->toMediaCollection('featureImages', 'guideFiles');
            }


            if ($request->hasFile('guideFile')) {
                $guide->clearMediaCollection('guides');

                $img =  $request->file('guideFile');
                $ext = $img->getClientOriginalExtension();
                $guideFile =  Str::slug($request->title) . "." . $ext;
                $guide->addMediaFromRequest('guideFile')->usingFileName($guideFile)->toMediaCollection('guides', 'guideFiles');
            }


            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                $guide->approval_id = Auth::user()->id;

                if (in_array($request->is_approved, ["approved", "rejected"])) {
                    $guide->is_approved = $request->is_approved;
                }
            } else {
                $guide->is_approved = "requested";
                $guide->approval_id = null;
            }

            $guide->updated_by = Auth::user()->id;
            $guide->save();

            $guide->slider_image = $guide->sliderImage;
            $guide->feature_image = $guide->featureImage;
            $guide->guide_file = $guide->guide;
            $guide->save();

            if ($request->has('tagIds')) {
                $guide->tags()->delete();
                foreach ($request->tagIds as $tag) {

                    if (TagCategory::where(['id' => $tag, 'type' => config('constants.guide'), 'status' => config('constants.active')])->exists()) {
                        $newtag = TagCategory::where(['id' => $tag, 'type' => config('constants.guide'), 'status' => config('constants.active')])->first();
                        $tag = $newtag->id;
                    } else {
                        $newtag = TagCategory::create(['name' => $tag, 'type' => config('constants.guide'), 'status' => config('constants.active'), 'user_id' => Auth::user()->id]);
                        $tag = $newtag->id;
                    }

                    $guide->tags()->create(['tag_category_id' => $tag]);
                }
            } else {
                $guide->tags()->delete();
            }
            DB::commit();
            return redirect()->route('dashboard.pageContents.dubaiGuide-page')->with('success', 'Guide has been updated successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.pageContents.dubaiGuide-page')->with('error', $error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($page, Guide $guide)
    {
        try {
            $guide->delete();
            return redirect()->route('dashboard.pageContents.dubaiGuide-page')->with('success', 'Guide has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.pageContents.dubaiGuide-page')->with('error', $error->getMessage());
        }
    }
}
