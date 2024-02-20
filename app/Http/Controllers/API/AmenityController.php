<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\AmenityRequest;
use App\Http\Resources\{
    AmenitiesNameResource,
};
use App\Models\Amenity;
use Auth;

class AmenityController extends Controller
{
   
    public function index(Request $request)
    {
        try{
            $amenities = Amenity::active()->approved()->get()->map(function ($amenity) {
                              return [
                                'id' => $amenity->id,
                                'name' => $amenity->name,
                              ];
                            });
            return $this->success('Amenities', $amenities, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage(), $exception->getCode());
        }
    }
    public function propertyAmenities()
    {
        try{
            $amenities = Amenity::active()->approved()->whereHas('properties', function ($query) {
                        $query->active()->approved();
                    })->get();
                    
            return $this->success('Amenities', AmenitiesNameResource::collection($amenities), 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage(), $exception->getCode());
        }    
    }
    public function projectAmenities()
    {
        try{
            $amenities = Amenity::active()->approved()->whereHas('projects', function ($query) {
                        $query->mainProject()->active()->approved();
                    })->get();
                    
            return $this->success('Amenities', AmenitiesNameResource::collection($amenities), 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage(), $exception->getCode());
        }
    }    
}
