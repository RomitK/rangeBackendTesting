<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\OfferTypeRequest;
use App\Models\OfferType;
use Auth;

class OfferTypeController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.real_estate'),
        ['only' => ['index','create', 'edit', 'update', 'destroy', 'destroySubFloor']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $offerTypes = OfferType::with('user')
        ->applyFilters($request->only(['status']))
        ->orderBy('id','desc')
        ->get();
        return view('dashboard.realEstate.offerTypes.index', compact('offerTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.realEstate.offerTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OfferTypeRequest $request)
    {
        try{
            $offerType = new OfferType;
            $offerType->name = $request->name;
            $offerType->status = $request->status;
            $offerType->user_id = Auth::user()->id;
            $offerType->save();
            return redirect()->route('dashboard.offer-types.index')->with('success','Offer Type has been created successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.offer-types.index')->with('error',$error->getMessage());
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
    public function edit(OfferType $offerType)
    {
        return view('dashboard.realEstate.offerTypes.edit',compact('offerType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OfferTypeRequest $request, OfferType $offerType)
    {
        try{
            $offerType->name = $request->name;
            $offerType->status = $request->status;
            $offerType->save();

            return redirect()->route('dashboard.offer-types.index')->with('success','Offer Type has been updated successfully');
        }catch(\Exception $error){
            return redirect()->route('dashboard.offer-types.index')->with('error',$error->getMessage());
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
            OfferType::find($id)->delete();

            return redirect()->route('dashboard.offer-types.index')->with('success','Offer Type has been deleted successfully');
        }catch(\Exception $error){
            return redirect()->route('dashboard.offer-types.index')->with('error',$error->getMessage());
        }

    }
}
