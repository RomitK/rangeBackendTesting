<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\DeveloperRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Str;
use App\Models\{
    Developer,
    TagCategory,
    MetaDetail,
    Project
};
use Auth;
use DB;

class DeveloperController extends Controller
{
    function __construct()
    {
        $this->middleware(
            'permission:' . config('constants.Permissions.real_estate'),
            ['only' => [
                'index', 'create', 'edit', 'update', 'destroy',
                'details', 'createDetail', 'storeDetail', 'editDetail', 'updateDetail', 'destroyDetail'
            ]]
        );
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page_size = 25;
        $current_page = isset($request->item) ? $request->item : $page_size;
        if (isset($request->page)) {
            $sr_no_start = ($request->page * $current_page) - $current_page + 1;
        } else {
            $sr_no_start = 1;
        }


        $collection = Developer::with('user')->approved();

        if (isset($request->status)) {
            $collection->where('status', $request->status);
        }
        if (isset($request->keyword)) {
            $keyword = $request->keyword;
            $collection->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%");
            });
        }
        if (isset($request->is_approved)) {
            $collection->where('is_approved', $request->is_approved);
        }

        if (isset($request->orderby)) {
            $orderBy = $request->input('orderby', 'created_at'); // default_column is the default field to sort by
            $direction = $request->input('direction', 'asc'); // Default sorting direction
            $developers = $collection->orderByRaw('ISNULL(developerOrder)')->orderBy($orderBy, $direction)->paginate($current_page);
        } else {
            $developers = $collection->latest()->paginate($current_page);
        }

        return view('dashboard.realEstate.developers.index', compact(
            'developers',
            'sr_no_start',
            'current_page'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = TagCategory::active()->developerTag()->orderBy('id', 'desc')->get();
        return view('dashboard.realEstate.developers.create', compact('tags'));
    }
    public function mainImage()
    {
        foreach (Developer::where('logo_image', null)->latest()->get() as $developer) {
            $developer->logo_image = $developer->logo;
            $developer->save();
            echo "developer-" . $developer->id;
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeveloperRequest $request)
    {
        try {
            $developer = new Developer;
            $developer->name = $request->name;
            $developer->status = $request->status;
            $developer->display_on_home = $request->display_on_home;
            $developer->developerOrder = $request->developerOrder;
            $developer->user_id = Auth::user()->id;
            // $developer->orderBy = $request->orderBy;
            $developer->short_description = $request->short_description;
            $developer->long_description = $request->long_description;
            $developer->meta_title = $request->meta_title;
            $developer->meta_description = $request->meta_description;
            $developer->meta_keywords = $request->meta_keywords;

            if ($request->hasFile('logo')) {
                $logo =  $request->file('logo');
                $ext = $logo->getClientOriginalExtension();
                $logoName =  Str::slug($request->name) . '_logo.' . $ext;
                $developer->addMediaFromRequest('logo')->usingFileName($logoName)->toMediaCollection('logos', 'developerFiles');
            }
            if ($request->hasFile('image')) {
                $img =  $request->file('image');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name) . '_image.' . $ext;
                $developer->addMediaFromRequest('image')->usingFileName($imageName)->toMediaCollection('images', 'developerFiles');
            }
            if ($request->hasFile('video')) {
                $video =  $request->file('video');
                $ext = $video->getClientOriginalExtension();
                $videoName =  Str::slug($request->name) . '.' . $ext;
                $developer->addMediaFromRequest('video')->usingFileName($videoName)->toMediaCollection('videos', 'developerFiles');
            }
            if ($request->has('gallery')) {
                foreach ($request->gallery as $key => $img) {
                    if (array_key_exists("file", $img) && $img['file']) {
                        $title = $img['title'] ?? $request->name;
                        $order =  $img['order'] ?? null;

                        $developer->addMedia($img['file'])
                            ->withCustomProperties([
                                'title' => $title,
                                'order' => $order
                            ])
                            ->toMediaCollection('gallery', 'developerFiles');
                    }
                }
            }

            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                $developer->is_approved = config('constants.approved');
                $developer->approval_id = Auth::user()->id;
            } else {
                //$developer->is_approved = config('constants.requested' );
                $developer->is_approved = "requested";
            }

            $developer->updated_by = Auth::user()->id;
            $developer->save();

            $developer->logo_image = $developer->logo;
            $developer->save();
            if ($request->has('tagIds')) {
                foreach ($request->tagIds as $tag) {
                    $developer->tags()->create(['tag_category_id' => $tag]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Developer has been created successfully.',
                'redirect' => route('dashboard.developers.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.developers.index'),
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
    public function edit(Developer $developer)
    {
        $tags = TagCategory::active()->developerTag()->orderBy('id', 'desc')->get();
        return view('dashboard.realEstate.developers.edit', compact('developer', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DeveloperRequest $request, Developer $developer)
    {
        DB::beginTransaction();
        try {
            $developer->name = $request->name;
            $developer->status = $request->status;
            $developer->display_on_home = $request->display_on_home;
            $developer->developerOrder = $request->developerOrder;
            $developer->short_description = $request->short_description;
            // $developer->orderBy = $request->orderBy;
            $developer->long_description = $request->long_description;
            $developer->meta_title = $request->meta_title;
            $developer->meta_description = $request->meta_description;
            $developer->meta_keywords = $request->meta_keywords;


            if ($request->hasFile('logo')) {
                $developer->clearMediaCollection('logos');
                $logo =  $request->file('logo');
                $ext = $logo->getClientOriginalExtension();
                $logoName =  Str::slug($request->name) . '_logo.' . $ext;
                $developer->addMediaFromRequest('logo')->usingFileName($logoName)->toMediaCollection('logos', 'developerFiles');
            }
            if ($request->hasFile('image')) {
                $developer->clearMediaCollection('images');
                $img =  $request->file('image');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name) . '_image.' . $ext;
                $developer->addMediaFromRequest('image')->usingFileName($imageName)->toMediaCollection('images', 'developerFiles');
            }
            if ($request->hasFile('video')) {
                $developer->clearMediaCollection('videos');
                $video =  $request->file('video');
                $ext = $video->getClientOriginalExtension();
                $videoName =  Str::slug($request->name) . '.' . $ext;
                $developer->addMediaFromRequest('video')->usingFileName($videoName)->toMediaCollection('videos', 'developerFiles');
            }

            if ($request->has('gallery')) {
                foreach ($request->gallery as $img) {
                    $title = $img['title'] ?? $request->name;
                    $order =  $img['order'] ?? null;

                    if ($img['old_gallery_id'] > 0) {

                        $mediaItem = Media::find($img['old_gallery_id']);
                        $mediaItem->setCustomProperty('title', $title);
                        $mediaItem->setCustomProperty('order', $order);
                        $mediaItem->save();
                    } else {
                        if (array_key_exists("file", $img) && $img['file']) {
                            $developer->addMedia($img['file'])
                                ->withCustomProperties([
                                    'title' => $title,
                                    'order' => $order
                                ])
                                ->toMediaCollection('gallery', 'developerFiles');
                        }
                    }
                }
            }
            // if(in_array(Auth::user()->role, config('constants.isAdmin'))){
            //     $developer->approval_id = Auth::user()->id;
            // }

            // $developer->is_approved = $request->is_approved;




            if (in_array(Auth::user()->role, config('constants.isAdmin'))) {
                $developer->approval_id = Auth::user()->id;

                if (in_array($request->is_approved, ["approved", "rejected"])) {
                    $developer->is_approved = $request->is_approved;
                }
            } else {
                $developer->is_approved = "requested";
                $developer->approval_id = null;
            }
            $developer->updated_by = Auth::user()->id;

            $developer->save();

            $developer->logo_image = $developer->logo;
            $developer->save();

            if ($request->has('tagIds')) {
                $developer->tags()->delete();
                foreach ($request->tagIds as $tag) {
                    $developer->tags()->create(['tag_category_id' => $tag]);
                }
            }

            Project::where('developer_id', $developer->id)->update(['updated_brochure' => 0]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Developer has been updated successfully.',
                'redirect' => route('dashboard.developers.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.developers.index'),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Developer $developer)
    {
        try {
            $developer->tags()->delete();
            $developer->delete();

            return redirect()->route('dashboard.developers.index')->with('success', 'Developer has been deleted successfully');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.developers.index')->with('error', $error->getMessage());
        }
    }

    public function mediaDestroy(Developer $developer, $media)
    {
        try {
            $developer->deleteMedia($media);
            return redirect()->route('dashboard.developers.edit', $developer->id)->with('success', 'Developer Image has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.developers.edit', $developer->id)->with('error', $error->getMessage());
        }
    }
    public function mediasDestroy(Developer $developer)
    {
        try {
            $developer->clearMediaCollection('gallery');
            return redirect()->route('dashboard.developers.edit', $developer->id)->with('success', 'Developer Gallery has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.developers.edit', $developer->id)->with('error', $error->getMessage());
        }
    }

    public function details(Developer $developer)
    {
        return view('dashboard.realEstate.developers.details.index', compact('developer'));
    }
    public function createDetail(Developer $developer)
    {
        return view('dashboard.realEstate.developers.details.create', compact('developer'));
    }
    public function storeDetail(Request $request, Developer $developer)
    {
        DB::beginTransaction();
        try {
            $specification = $developer->details()->create(['name' => $request->name, 'value' => $request->value]);
            DB::commit();
            return redirect()->route('dashboard.developer.details', [$developer->id])->with('success', 'Developer Detail has been created successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.developer.details', [$developer->id])->with('error', $error->getMessage());
        }
    }
    public function editDetail(Developer $developer, MetaDetail $detail)
    {
        return view('dashboard.realEstate.developers.details.edit', compact('developer', 'detail'));
    }
    public function updateDetail(Request $request, Developer $developer, MetaDetail $detail)
    {
        DB::beginTransaction();
        try {
            $detail->name = $request->name;
            $detail->value = $request->value;

            $detail->save();
            DB::commit();
            return redirect()->route('dashboard.developer.details', [$developer->id])->with('success', 'Developer Detail has been updated successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.developer.details', [$developer->id])->with('error', $error->getMessage());
        }
    }
    public function destroyDetail(Developer $developer, MetaDetail $detail)
    {
        DB::beginTransaction();
        try {
            $detail->delete();
            DB::commit();
            return redirect()->route('dashboard.developer.details', [$developer->id])->with('success', 'Developer Detail has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.developer.details', [$developer->id])->with('error', $error->getMessage());
        }
    }
}
