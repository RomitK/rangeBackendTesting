<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\AmenityRequest;
use App\Models\Amenity;
use Auth;

class AmenityController extends Controller
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
        
        $collection = Amenity::with('user');
        if (isset($request->status)) {
            $collection->where('status', $request->status);
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
        $amenities = $collection->latest()->paginate($current_page);

        return view('dashboard.realEstate.amenities.index', compact('amenities', 'sr_no_start',
            'current_page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.realEstate.amenities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AmenityRequest $request)
    {
        try{
            $amenity = new Amenity;
            $amenity->name = $request->name;
            $amenity->status = $request->status;
            $amenity->user_id = Auth::user()->id;
            
             if(in_array(Auth::user()->role, config('constants.isAdmin'))){
                $amenity->is_approved = config('constants.approved' );
                $amenity->approval_id = Auth::user()->id;
                
            }else{
                $amenity->is_approved = config('constants.requested' );
            }
            
            if ($request->hasFile('image')) {
                $amenity->addMediaFromRequest('image')->toMediaCollection('images', 'amenityFiles');
            }
            if(isset($request->is_approved)){
             $collection->where('is_approved', $request->is_approved);
            }
            $amenity->save();
            return response()->json([
                'success' => true,
                'message'=> 'Amenity has been created successfully.',
                'redirect' => route('dashboard.amenities.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.amenities.index'),
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
    public function edit(Amenity $amenity)
    {
        return view('dashboard.realEstate.amenities.edit',compact('amenity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AmenityRequest $request, Amenity $amenity)
    {
        try{
            $amenity->name = $request->name;
            $amenity->status = $request->status;
            
            if(in_array(Auth::user()->role, config('constants.isAdmin'))){
                $amenity->approval_id = Auth::user()->id;
            }
            
            $amenity->is_approved = $request->is_approved;
            
             if ($request->hasFile('image')) {
                $amenity->clearMediaCollection('images');
                $amenity->addMediaFromRequest('image')->toMediaCollection('images', 'amenityFiles');
            }
            
            $amenity->save();
            return response()->json([
                'success' => true,
                'message'=> 'Amenity has been updated successfully.',
                'redirect' => route('dashboard.amenities.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.amenities.index'),
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
            Amenity::find($id)->delete();

            return redirect()->route('dashboard.amenities.index')->with('success','Amenity has been deleted successfully');

        }catch(\Exception $error){
            return redirect()->route('dashboard.amenities.index')->with('error',$error->getMessage());
        }


    }
}
