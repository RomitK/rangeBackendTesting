<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\AwardRequest;
use Illuminate\Support\Str;
use App\Models\{
    Award,
    Developer
};
use Auth;
use DB;
class AwardController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.awards'),
        ['only' => ['index','create', 'edit', 'update', 'destroy', 'mediaDestroy', 'mediasDestroy']
        ]);
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
        
        
        $collection = Award::with('user');
        
        if (isset($request->status)) {
            $collection->where('status', $request->status);
        }
        
        if (isset($request->keyword)) {
            $keyword = $request->keyword;
            $collection->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%");
            });
        }
        
        if(isset($request->orderby)){
            $orderBy = $request->input('orderby', 'created_at'); // default_column is the default field to sort by
            $direction = $request->input('direction', 'asc'); // Default sorting direction
            $awards = $collection->orderByRaw('ISNULL(awardOrder)')->orderBy($orderBy, $direction)->paginate($current_page);
            

        }else{
            $awards = $collection->latest()->paginate($current_page);
        }
        
        
        

        return view('dashboard.awards.index', compact('awards', 'sr_no_start',
            'current_page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $developers = Developer::active()->latest()->get();
        return view('dashboard.awards.create', compact('developers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AwardRequest $request)
    {
        try{
            $award = new Award;
            $award->title = $request->title;
            $award->position = $request->position;
            $award->year = $request->year;
            $award->awardOrder = $request->awardOrder;
            $award->status = $request->status;
            $award->user_id = Auth::User()->id;
            if($request->has('developer_id')){
                $award->developer()->associate($request->developer_id);
            }
            if ($request->hasFile('trophy')) {
                $img =  $request->file('trophy');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title).'_trophy.'.$imgExt;
                $award->addMediaFromRequest('trophy')->usingFileName($imageName)->toMediaCollection('trophies', 'awardFiles');

            }
            if ($request->hasFile('badge')) {
                $img =  $request->file('badge');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title).'_badge.'.$imgExt;
                $award->addMediaFromRequest('badge')->usingFileName($imageName)->toMediaCollection('badges', 'awardFiles');

            }
            if ($request->hasFile('gallery')) {
                foreach($request->gallery as $img)
                {
                    $award->addMedia($img)->toMediaCollection('gallery', 'awardFiles');
                }
            }
            $award->save();
            return response()->json([
                'success' => true,
                'message'=> 'Award has been created successfully.',
                'redirect' => route('dashboard.awards.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.awards.index'),
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
    public function edit(Award $award)
    {
        $developers = Developer::active()->latest()->get();
        return view('dashboard.awards.edit', compact('award', 'developers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AwardRequest $request, Award $award)
    {
        try{
            $award->title = $request->title;
            $award->position = $request->position;
            $award->year = $request->year;
            $award->awardOrder = $request->awardOrder;
            $award->status = $request->status;

            if($request->has('developer_id')){
                $award->developer()->associate($request->developer_id);
            }
            if ($request->hasFile('trophy')) {
                $award->clearMediaCollection('trophies');
                $img =  $request->file('trophy');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title).'_trophy.'.$imgExt;
                $award->addMediaFromRequest('trophy')->usingFileName($imageName)->toMediaCollection('trophies', 'awardFiles');

            }
            if ($request->hasFile('badge')) {
                $award->clearMediaCollection('badges');
                $img =  $request->file('badge');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title).'_badge.'.$imgExt;
                $award->addMediaFromRequest('badge')->usingFileName($imageName)->toMediaCollection('badges', 'awardFiles');

            }
            if ($request->hasFile('gallery')) {
               
                foreach ($request->gallery as $img) {
                    $award->addMedia($img)->toMediaCollection('gallery', 'awardFiles');
                }
            }
            $award->save();
            return response()->json([
                'success' => true,
                'message'=> 'Award has been updated successfully.',
                'redirect' => route('dashboard.awards.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.awards.index'),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Award $award)
    {
        try{
            $award->delete();
            return redirect()->route('dashboard.awards.index')->with('success','Award has been deleted successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.awards.index')->with('error',$error->getMessage());
        }
    }
    public function mediaDestroy(Award $award, $media)
    {
        try{
            $award->deleteMedia($media);
            return redirect()->route('dashboard.awards.edit', $award->id)->with('success','Award Image has been deleted successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.awards.edit', $award->id)->with('error',$error->getMessage());
        }
    }
    public function mediasDestroy(Award $award)
    {
        try{
            $award->clearMediaCollection('gallery');
            return redirect()->route('dashboard.awards.edit', $award->id)->with('success','Award Gallery has been deleted successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.awards.edit', $award->id)->with('error',$error->getMessage());
        }
    }
}
