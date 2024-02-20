<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\HighlightRequest;
use App\Models\Highlight;
use Auth;

class HighlightController extends Controller
{
    function __construct()
    {
        //$this->middleware('permission:'.config('constants.Permissions.real_estate'), ['only' => ['index','create', 'edit', 'update', 'destroy']]);
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
        
        $collection = Highlight::with('user');
        if (isset($request->status)) {
            $collection->where('status', $request->status);
        }
        if(isset($request->is_approved)){
             $collection->where('is_approved', $request->is_approved);
        }
        if (isset($request->keyword)) {
            $keyword = $request->keyword;
            $collection->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%");
            });
        }
        $highlights = $collection->latest()->paginate($current_page);
        return view('dashboard.realEstate.highlights.index', compact(
            'highlights',
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
        return view('dashboard.realEstate.highlights.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HighlightRequest $request)
    {
        try{
            $highlight = new Highlight;
            $highlight->name = $request->name;
            $highlight->status = $request->status;
            $highlight->user_id = Auth::user()->id;
            
            if(in_array(Auth::user()->role, config('constants.isAdmin'))){
                $highlight->is_approved = config('constants.approved' );
                $highlight->approval_id = Auth::user()->id;
                
            }else{
                $highlight->is_approved = config('constants.requested' );
            }
            
            if ($request->hasFile('image')) {
                $highlight->addMediaFromRequest('image')->toMediaCollection('images', 'highlightFiles');
            }
            $highlight->save();
            return response()->json([
                'success' => true,
                'message'=> 'Highlight has been created successfully.',
                'redirect' => route('dashboard.highlights.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.highlights.index'),
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
    public function edit(Highlight $highlight)
    {
        return view('dashboard.realEstate.highlights.edit',compact('highlight'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HighlightRequest $request, Highlight $highlight)
    {
        try{
            $highlight->name = $request->name;
            $highlight->status = $request->status;
            if(in_array(Auth::user()->role, config('constants.isAdmin'))){
                $highlight->approval_id = Auth::user()->id;
            }
            $highlight->is_approved = $request->is_approved;
            
            if ($request->hasFile('image')) {
                $highlight->clearMediaCollection('images');
                $highlight->addMediaFromRequest('image')->toMediaCollection('images', 'highlightFiles');
            }
            $highlight->save();
            return response()->json([
                'success' => true,
                'message'=> 'Highlight has been updated successfully.',
                'redirect' => route('dashboard.highlights.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.highlights.index'),
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
            Highlight::find($id)->delete();

            return redirect()->route('dashboard.highlights.index')->with('success','Highlight has been deleted successfully');

        }catch(\Exception $error){
            return redirect()->route('dashboard.highlights.index')->with('error',$error->getMessage());
        }


    }
}
