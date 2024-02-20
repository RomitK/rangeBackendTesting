<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\Dashboard\CareerRequest;
use App\Http\Requests\JobApplicationRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\{
    Career, 
    CareerApplicant,
    WebsiteSetting,
    Counter
};
use App\Http\Resources\{
    CareerListResource,
    CareerCounterResource,
    SingleCareerResource
};
use Auth;
use DB;
class CareerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            
            $counters =  Counter::where('page_name',config('constants.career'))->active()->get();
           
            $careers = Career::active()->get();
            $data = [
                'counters'=>CareerCounterResource::collection($counters),
                'careers'=>CareerListResource::collection($careers)
                ];
            return $this->success('All careers', $data, 200);
           
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    
    public function saveJobApplication(JobApplicationRequest $request)
    {
        $application = CareerApplicant::create($request->validated());
        $application->addMediaFromRequest('cv')->usingFileName($request->name)->toMediaCollection('CVS', 'careerFiles');
        return response()->json(['message' => 'Application submitted successfully']);
    }
    
    public function careerForm(Request $request)
    {
       
        try{
            
            $messages = [
                'careerId'=> 'careerId is Required',
                'name' => 'Name is Required ',
                'email' => 'Email is Required ',
                'phone' => 'Mobile No. is Required ',
                'cv' => 'Please upload CV',
            ];
            $validator = Validator::make($request->all(), [
                'careerId'=> 'required',
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'cv' => 'required',
            ], $messages);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $career = new CareerApplicant;
            if ($request->has('careerId')) {
                $career->career_id = $request->careerId;
            }
    
            $career->name = $request->name;
            $career->email = $request->email;
            $career->contact_number = $request->full_number;
            $career->cover_letter = $request->cover_letter;
            if ($request->hasFile('cv')) {
                $img =  $request->file('cv');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name).'.'.$imgExt;
                $career->addMedia($img)->usingFileName($imageName)->toMediaCollection('CVS', 'careerFiles');
            }
    
            $career->submit_date = date('Y-m-d H:i:s');
            $career->save();
        
            return $this->success('Form Submit', [], 200);
            
            }catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function singleCareerMeta($slug){
        try{
            if(Career::where('slug', $slug)->exists()){
                $career = DB::table('careers')->select('meta_title','position', 'meta_description', 'meta_keywords')->where('slug', $slug)->first();
                $singleCareer = (object)[];
                   
                if($career->meta_title){
                    $singleCareer->meta_title = $career->meta_title;
               }else{
                   $singleCareer->meta_title = WebsiteSetting::getSetting('website_name')? WebsiteSetting::getSetting('website_name') : '' ;
               }
               if($career->meta_description){
                    $singleCareer->meta_description = $career->meta_description;
               }else{
                   $singleCareer->meta_description = WebsiteSetting::getSetting('description')? WebsiteSetting::getSetting('description') : '' ;
               }
               
               if($career->meta_keywords){
                    $singleCareer->meta_keywords = $career->meta_keywords;
               }else{
                   $singleCareer->meta_keywords = WebsiteSetting::getSetting('keywords')? WebsiteSetting::getSetting('keywords') : '' ;
               }
               
                return $this->success('Single Career Meta', $singleCareer, 200);
            }else{
                return $this->success('Single Career Meta', [], 200);
            }
        }catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }  
    }
    public function singleCareer($slug)
    {
        try{
            
            if(Career::where('slug', $slug)->exists()){
                $career = Career::where('slug', $slug)->first();
                
                $singleCareer = (object)[];
               
                $singleCareer->id = $career->id;
                $singleCareer->position = $career->position;
                $singleCareer->slug = $career->slug;
                $singleCareer->type = $career->type;
                $singleCareer->location = $career->location;
                $singleCareer->description = $career->description->render();
                $singleCareer->responsibilities = $career->key_responsibilities->render();
                $singleCareer->requirements = $career->requirements->render();
               
                if($career->meta_title){
                    $singleCareer->title = $career->meta_title;
               }else{
                   $singleCareer->title = WebsiteSetting::getSetting('website_name')? WebsiteSetting::getSetting('website_name') : '' ;
               }
               if($career->meta_description){
                    $singleCareer->meta_description = $career->meta_description;
               }else{
                   $singleCareer->meta_description = WebsiteSetting::getSetting('description')? WebsiteSetting::getSetting('description') : '' ;
               }
               
               if($career->meta_keyword){
                    $singleCareer->meta_keyword = $career->meta_keyword;
               }else{
                   $singleCareer->meta_keyword = WebsiteSetting::getSetting('keywords')? WebsiteSetting::getSetting('keywords') : '' ;
               }
                
                return $this->success('Single Management', $singleCareer, 200);
            }else{
                return $this->success('Single Management', [], 200);
            }
           
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
     }
     
    public function singleCareerDetail($slug)
    {
        try{
            if(Career::where('slug', $slug)->exists()){
                $career = Career::where('slug', $slug)->first();
                $career = new SingleCareerResource($career);
                
                return $this->success('Single Career', $career, 200);
            }else{
                return $this->success('Single Career', [], 200);
            }
           
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
     }
}
