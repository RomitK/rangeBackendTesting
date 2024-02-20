<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\AccommodationRequest;
use App\Models\{
    Accommodation, 
    Project, 
    Property
    
};
use App\Http\Resources\{
    AccommodationListResource,
};
use Auth;

class AccommodationController extends Controller
{
    public function index(Request $request)
    {
        try{
            $accommodations = Accommodation::active()->approved()->get();
            $accommodations =  AccommodationListResource::collection($accommodations);
            
           
            return $this->success('Accommodations',$accommodations , 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage(), $exception->getCode());
        }
    }
    public function developerAccommodationOptions(Request $request)
    {
        try{
            
            $accommodations = Accommodation::whereIn('id',Project::approved()->active()->mainProject()->pluck('accommodation_id')->toArray())->active()->approved()->get()->map(function($accommodation){
                return [
                    'value'=>$accommodation->id,
                    'label'=>$accommodation->name
                ];
            });
            $accommodations->prepend([
                    'value'=>'',
                    'label'=>'All'
            ]);
            
          
           
            return $this->success('Accommodations',$accommodations , 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    
    public function communityAccommodationOptions(Request $request)
    {
        try{
             $accommodations = Accommodation::whereIn('id',Project::approved()->active()->mainProject()->pluck('accommodation_id')->toArray())->active()->approved()->get()->map(function($accommodation){
                return [
                    'value'=>$accommodation->id,
                    'label'=>$accommodation->name
                ];
            });
            $accommodations->prepend([
                    'value'=>'',
                    'label'=>'All'
            ]);
            
           
            return $this->success('Accommodations',$accommodations , 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage(), $exception->getCode());
        }
    }
    
    public function accommodationOptions()
    {
        try{
            $accommodations = Accommodation::active()->approved()->get()->map(function($accommodation){
                return [
                    'value'=>$accommodation->id,
                    'label'=>$accommodation->name
                ];
            });
            $accommodations->prepend([
                    'value'=>'',
                    'label'=>'All'
            ]);
            return $this->success('Accommodation Options', $accommodations, 200);
           
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
    public function propertyAccommodations(Request $request)
    {
        try{
            $accommodations = Accommodation::active()->where('for_property',1)->approved()->get()->map(function ($accommodation) {
                              return [
                                'id' => $accommodation->id,
                                'name' => $accommodation->name,
                              ];
                            });
            $accommodations->prepend([
                    'value'=>'',
                    'label'=>'All'
            ]);
            return $this->success('Accommodations', $accommodations, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage(), $exception->getCode());
        }
    }
    
    public function projectAccommodations()
    {
       
        try{
            $accommodations = Accommodation::whereIn('id',Project::approved()->active()->mainProject()->pluck('accommodation_id')->toArray())->active()->approved()->get();
            $accommodations =  AccommodationListResource::collection($accommodations);
            
           
            return $this->success('Accommodations',$accommodations , 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage(), $exception->getCode());
        }
    }
    
    public function propertyAccommodationLists()
    {
       
        try{
            $accommodations = Accommodation::whereIn('id',Property::approved()->active()->pluck('accommodation_id')->toArray())->active()->approved()->get();
            $accommodations =  AccommodationListResource::collection($accommodations);
            
           
            return $this->success('Accommodations',$accommodations , 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage(), $exception->getCode());
        }
    }
    
}
