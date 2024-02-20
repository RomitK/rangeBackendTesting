<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\AccommodationRequest;
use App\Models\Accommodation;
use Auth;

class AccommodationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.real_estate'), ['only' => ['index','create', 'edit', 'update', 'destroy']]);
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
        
        $collection = Accommodation::with('user');
        
        if (isset($request->status)) {
            $collection->where('status', $request->status);
        }
        if (isset($request->type)) {
            $collection->where('type', $request->type);
        }
        if (isset($request->keyword)) {
            $keyword = $request->keyword;
            $collection->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%");
            });
        }
        if(isset($request->is_approved)){
             $collection->where('is_approved', $request->is_approved);
        }
         $accommodations = $collection->latest()->paginate($current_page);

        return view('dashboard.realEstate.accommodations.index', compact(
            'accommodations',
            'sr_no_start',
            'current_page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        return view('dashboard.realEstate.accommodations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccommodationRequest $request)
    {
       
        try{
            $accommodation = new Accommodation;
            $accommodation->name = $request->name;
            $accommodation->status = $request->status;
            $accommodation->type = $request->type;
            $accommodation->user_id = Auth::user()->id;
            if(in_array(Auth::user()->role, config('constants.isAdmin'))){
                $accommodation->is_approved = config('constants.approved' );
                $accommodation->approval_id = Auth::user()->id;
                
            }else{
                $accommodation->is_approved = config('constants.requested' );
            }
            
            if ($request->hasFile('image')) {
            $accommodation->addMediaFromRequest('image')->toMediaCollection('images');
            }
            $accommodation->save();
            return response()->json([
                'success' => true,
                'message'=> 'Accommodation has been created successfully.',
                'redirect' => route('dashboard.accommodations.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.accommodations.index'),
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
    public function edit(Accommodation $accommodation)
    {
        return view('dashboard.realEstate.accommodations.edit',compact('accommodation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AccommodationRequest $request, Accommodation $accommodation)
    {
        try{
            $accommodation->name = $request->name;
            $accommodation->status = $request->status;
            $accommodation->type = $request->type;
            
            if(in_array(Auth::user()->role, config('constants.isAdmin'))){
                $accommodation->approval_id = Auth::user()->id;
            }
            
            $accommodation->is_approved = $request->is_approved;
            if ($request->hasFile('image')) {
                $accommodation->clearMediaCollection('images');
                $accommodation->addMediaFromRequest('image')->toMediaCollection('images');
            }
            $accommodation->save();

            return response()->json([
                'success' => true,
                'message'=> 'Accommodation has been updated successfully.',
                'redirect' => route('dashboard.accommodations.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.accommodations.index'),
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
            Accommodation::find($id)->delete();

            return redirect()->route('dashboard.accommodations.index')->with('success','Accommodation has been deleted successfully');

        }catch(\Exception $error){
            return redirect()->route('dashboard.accommodations.index')->with('error',$error->getMessage());
        }

    }
}
