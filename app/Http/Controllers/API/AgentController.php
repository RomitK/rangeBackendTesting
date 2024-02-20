<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\AgentRequest;
use Illuminate\Support\Str;
use App\Models\{
    Agent,
    Language,
    Service,
    Developer,
    Community,
    Project,
    WebsiteSetting
};
use Auth;
use App\Http\Resources\{
    SingleManagementResource,
    ManagementListResource,
    AgentListResource
};
class AgentController extends Controller
{
   
    public function managements(Request $request)
    {
        try{
            $managements = Agent::active()->where('is_management', 1)->orderBy('OrderBy', 'asc')->get();
            $managements =  ManagementListResource::collection($managements);
            return $this->success('All Management', $managements, 200);
           
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function checkEmployeeId(Request $request)
    {
        try{
            
            return $this->success('check Employee Id', Agent::where('employeeId', $request->employeeId)->active()->exists(), 200);
           
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function singleManagement($slug)
    {
        try{
            
            if(Agent::where('slug', $slug)->where('is_management', 1)->exists()){
                $management = Agent::where('slug', $slug)->first();
                
                $singleManagement = (object)[];
               
                $singleManagement->id = "management_".$management->id;
                $singleManagement->name = $management->name;
                $singleManagement->slug = $management->slug;
                $singleManagement->email = $management->email;
                $singleManagement->designation = $management->designation;
                $singleManagement->contact = $management->contact_number;
                $singleManagement->image = $management->image;
                $singleManagement->video = $management->video;
                $singleManagement->message = $management->message->render();
               
                if($management->meta_title){
                    $singleManagement->title = $management->meta_title;
               }else{
                   $singleManagement->title = WebsiteSetting::getSetting('website_name')? WebsiteSetting::getSetting('website_name') : '' ;
               }
               if($management->meta_description){
                    $singleManagement->meta_description = $management->meta_description;
               }else{
                   $singleManagement->meta_description = WebsiteSetting::getSetting('description')? WebsiteSetting::getSetting('description') : '' ;
               }
               
               if($management->meta_keyword){
                    $singleManagement->meta_keyword = $management->meta_keyword;
               }else{
                   $singleManagement->meta_keyword = WebsiteSetting::getSetting('keywords')? WebsiteSetting::getSetting('keywords') : '' ;
               }
                
                return $this->success('Single Management', $singleManagement, 200);
            }else{
                return $this->success('Single Management', [], 200);
            }
           
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }    
    }
    public function singleManagementMeta($slug){
        try{
            
            if(Agent::where('slug', $slug)->where('is_management', 1)->exists()){
                $management = Agent::where('slug', $slug)->first();
                
                $singleManagement = (object)[];
                
                if($management->meta_title){
                    $singleManagement->title = $management->meta_title;
               }else{
                   $singleManagement->title = WebsiteSetting::getSetting('website_name')? WebsiteSetting::getSetting('website_name') : '' ;
               }
               if($management->meta_description){
                    $singleManagement->meta_description = $management->meta_description;
               }else{
                   $singleManagement->meta_description = WebsiteSetting::getSetting('description')? WebsiteSetting::getSetting('description') : '' ;
               }
               
               if($management->meta_keywords){
                    $singleManagement->meta_keywords = $management->meta_keywords;
               }else{
                   $singleManagement->meta_keywords = WebsiteSetting::getSetting('keywords')? WebsiteSetting::getSetting('keywords') : '' ;
               }
                
                return $this->success('Single Management Meta', $singleManagement, 200);
            }else{
                return $this->success('Single Management', [], 200);
            }
           
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function singleManagementDetail($slug)
    {
        try{
            
            if(Agent::where('slug', $slug)->where('is_management', 1)->exists()){
                $management = Agent::where('slug', $slug)->first();
                $management = new SingleManagementResource($management);
                
                return $this->success('Single Management', $management, 200);
            }else{
                return $this->success('Single Management', [], 200);
            }
           
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }    
    }
    
    public function agents(Request $request)
    {
        try{
            $agents = Agent::active()->where('is_management', 0)->orderBy('OrderBy', 'asc')->get();
            $agents =  AgentListResource::collection($agents);
               
            return $this->success('All Agents', $agents, 200);
           
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    
    public function agentLists(Request $request)
    {
        try{
            $agents = Agent::active()->where('is_management', 0)->orderBy('OrderBy', 'asc')->get()->map(function($agent){
                return [
                    'id'=>'agent_'.$agent->id,
                    'name'=>$agent->name,
                    'slug'=>$agent->slug,
                    'email'=>$agent->email,
                    'contact' => $agent->contact_number,
                    'whatsapp'=>$agent->whatsapp_number,
                    'image'=>$agent->image,
                    'designation'=>$agent->designation,
                    'languages'=>$agent->languages->pluck('name')
                ];
            });
            return $this->success('All Agents', $agents, 200);
           
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
}
