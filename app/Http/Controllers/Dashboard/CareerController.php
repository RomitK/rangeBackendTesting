<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\Dashboard\CareerRequest;
use App\Models\{Career, CareerApplicant};
use Auth;

class CareerController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.career_management'),
        ['only' => ['index','create', 'edit', 'update', 'destroy', 'applicants', 'singleApplicant', 'allApplicants', 'singleCareerApplicant']
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $applicants = CareerApplicant::count();
        $careers = Career::with('user')
        ->applyFilters($request->only(['status']))
        ->orderBy('id','desc')
        ->get();

        return view('dashboard.careers.index', compact('careers', 'applicants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jobTypes = Career::JOB_TYPE;
        return view('dashboard.careers.create', compact('jobTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CareerRequest $request)
    {
        try{
            $career = new Career;
            $career->position = $request->position;
            $career->post_date = $request->post_date;
            $career->status = $request->status;
            $career->type = $request->type;
            $career->location = $request->location;
            $career->short_description = $request->short_description;
            
            $career->key_responsibilities = $request->key_responsibilities;
            $career->requirements = $request->requirements;
            $career->assistance = $request->assistance;
            $career->benefits = $request->benefits;
            $career->meta_title = $request->meta_title;
            $career->meta_keywords = $request->meta_keywords;
            $career->meta_description = $request->meta_description;
            $career->user_id = Auth::user()->id;
            $career->save();
            return redirect()->route('dashboard.careers.index')->with('success','Job Post has been created successfully.');

        }catch(\Exception $error){
            return redirect()->route('dashboard.careers.index')->with('error',$error->getMessage());
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
    public function edit(Career $career)
    {
        $jobTypes = Career::JOB_TYPE;
        return view('dashboard.careers.edit',compact('career', 'jobTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CareerRequest $request, Career $career)
    {
        try{
            $career->position = $request->position;
            $career->post_date = $request->post_date;
            $career->type = $request->type;
            $career->location = $request->location;
            $career->status = $request->status;
            $career->short_description = $request->short_description;
            $career->key_responsibilities = $request->key_responsibilities;
            $career->requirements = $request->requirements;
            $career->assistance = $request->assistance;
            $career->benefits = $request->benefits;
            $career->meta_title = $request->meta_title;
            $career->meta_keywords = $request->meta_keywords;
            $career->meta_description = $request->meta_description;
            $career->generateSlug();
            $career->save();
            return redirect()->route('dashboard.careers.index')->with('success','Job Post has been updated successfully.');

        }catch(\Exception $error){
            return redirect()->route('dashboard.careers.index')->with('error',$error->getMessage());
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
            $career = Career::find($id);
            $career->delete();
            return redirect()->route('dashboard.careers.index')->with('success','Job Post has been deleted successfully');
        }catch(\Exception $error){
            return redirect()->route('dashboard.careers.index')->with('error',$error->getMessage());
        }
    }
    public function applicants(Career $career)
    {
        $applicants = CareerApplicant::where('career_id', $career->id)->latest()->get();
        return view('dashboard.careers.applicants',compact('career', 'applicants'));
    }
    public function allApplicants()
    {
        $allApplicants = CareerApplicant::latest()->get();
        $withoutCareerApplicants = $allApplicants->whereNull('career_id');
        $withoutCareerApplicants->all();

        $withApplicants = $allApplicants->whereNotNull('career_id');
        $withApplicants->all();

        return view('dashboard.careers.allApplicants',compact('withApplicants','allApplicants', 'withoutCareerApplicants'));

    }
    public function singleCareerApplicant(Career $career, CareerApplicant $applicant)
    {
        try{
            return view('dashboard.careers.applicant',compact('career', 'applicant'));
        }catch(\Exception $error){
            return redirect()->route('dashboard.careers.index')->with('error',$error->getMessage());
        }
    }
    public function deleteApplicant(Request $request, CareerApplicant $applicant)
    {
        try{
            $applicant->delete();
            if($request->has('career'))
            {
                $career = Career::find($request->career);
                return redirect()->route('dashboard.careers.applicants', $career->id)->with('success','Applicant has been updated successfully.');;
            }else{
                return redirect()->route('dashboard.careers.allApplicants')->with('success','Applicant has been updated successfully.');
            }
        }catch(\Exception $error){
            return redirect()->back()->with('error',$error->getMessage());
        }
    }
    public function singleApplicant(CareerApplicant $applicant)
    {
        try{
            $career = null;
            return view('dashboard.careers.applicant',compact('applicant'));
        }catch(\Exception $error){
            return redirect()->route('dashboard.careers.index')->with('error',$error->getMessage());
        }
    }
}
