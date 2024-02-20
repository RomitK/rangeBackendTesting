<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\ServiceRequest;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Str;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.services'),
        ['only' => ['index','create', 'edit', 'update', 'destroy']
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::with('user')
            ->latest()
            ->get();

        return view('dashboard.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parentServices = Service::mainService()->latest()->get();
        return view('dashboard.services.create', compact('parentServices'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceRequest $request)
    {
        try {
            $service = new Service;
            $service->name = $request->name;
            $service->status = $request->status;
            $service->is_parent = $request->is_parent;
            $service->description = $request->description;
            $service->meta_title = $request->meta_title;
            $service->meta_keywords = $request->meta_keywords;
            $service->meta_description = $request->meta_description;
            $service->user_id = Auth::user()->id;
            if ($request->hasFile('image')) {
                $img =  $request->file('image');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name).'.'.$imgExt;
                $service->addMediaFromRequest('image')->usingFileName($imageName)->toMediaCollection('images', 'serviceFiles');
            }
            if ($request->hasFile('icon')) {
                $icon =  $request->file('icon');
                $iconExt = $icon->getClientOriginalExtension();
                $iconName =  Str::slug($request->name).'.'.$iconExt;
                $service->addMediaFromRequest('icon')->usingFileName($iconName)->toMediaCollection('icons', 'serviceFiles');
            }
            if($request->has('parent_id') && $request->is_parent){
                $service->parent()->associate($request->parent_id);
            }else{
                $service->parent_id = null;
            }
            $service->save();
            return response()->json([
                'success' => true,
                'message'=> 'Service has been created successfully.',
                'redirect' => route('dashboard.services.index'),
            ]);


        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.services.index'),
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
    public function edit(Service $service)
    {
        $parentServices = Service::mainService()->latest()->get();
        return view('dashboard.services.edit', compact('service', 'parentServices'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceRequest $request, Service $service)
    {
        try {
            $service->name = $request->name;
            $service->status = $request->status;
            $service->is_parent = $request->is_parent;
            $service->description = $request->description;
            $service->meta_title = $request->meta_title;
            $service->meta_keywords = $request->meta_keywords;
            $service->meta_description = $request->meta_description;
            $service->user_id = Auth::user()->id;
            $service->generateSlug();
            if ($request->hasFile('image')) {
                $service->clearMediaCollection('images');
                $img =  $request->file('image');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name).'.'.$imgExt;
                $service->addMediaFromRequest('image')->usingFileName($imageName)->toMediaCollection('images', 'serviceFiles');
            }
            if ($request->hasFile('icon')) {
                $service->clearMediaCollection('icons');
                $icon =  $request->file('icon');
                $iconExt = $icon->getClientOriginalExtension();
                $iconName =  Str::slug($request->name).'.'.$iconExt;
                $service->addMediaFromRequest('icon')->usingFileName($iconName)->toMediaCollection('icons', 'serviceFiles');
            }
            if($request->has('parent_id')){
                $service->parent()->associate($request->parent_id);
            }
            $service->save();

            return response()->json([
                'success' => true,
                'message'=> 'Service has been Updated successfully.',
                'redirect' => route('dashboard.services.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.services.index'),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        try {
            $service->delete();
            return redirect()->route('dashboard.services.index')->with('success', 'Service has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.services.index')->with('error', $error->getMessage());
        }
    }
}
