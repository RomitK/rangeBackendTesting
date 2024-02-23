<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\{
    Accommodation,
    Developer,
    Category,
    Community,
    PageTag,
    Property,
    Project
    
};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function showLoginPage()
    {
        if (Auth::check()) {
            // If the user is already authenticated, redirect them to a different page
            return redirect()->route('dashboard');
        } else {
            // If the user is not authenticated, show the login page
            return view('auth.login');
        }
    }

    public function singlePropertyBrochure($slug)
    {
        $property = Property::where('slug', $slug)->first();
            view()->share([
                'property' => $property
            ]);
            
            $pdf = PDF::loadView('pdf.propertyBrochure');
        return $pdf->stream();
    }
    
     public function singlePropertySaleOffer($slug)
    {
        $property = Property::with('communities', 'project')->where('slug', $slug)->first();
            view()->share([
                'property' => $property,
            ]);
        
        $pdf = PDF::loadView('pdf.propertySaleOffer');
        return $pdf->stream();
    }
    
    public function singleProjectSaleOffer($slug)
    {
        $project = Project::with('developer', 'mainCommunity', 'subProjects')->where('slug', $slug)->first();
        $dateStr = $project->completion_date;
        $month = date("n", strtotime($dateStr));
        $yearQuarter = ceil($month / 3);
        
        view()->share([
            'project' => $project,
             'handOver' => "Q".$yearQuarter." ".date("Y", strtotime($dateStr)),
            
        ]);
        $pdf = PDF::loadView('pdf.projectSaleOffer');
        return $pdf->stream();
        //return $pdf->download($project->title.' Sale Offer.pdf');
    }
    public function singleProjectBrochure($slug)
    {
        $project = Project::with('developer', 'mainCommunity', 'subProjects')->where('slug', $slug)->first();
        $minBed = $project->subProjects->min('bedrooms');
        $maxBed = $project->subProjects->max('bedrooms');
        if($minBed != $maxBed){
            if($maxBed === "Studio"){
                $bedroom = $maxBed. "-".$minBed;
            }else{
                $bedroom = $minBed. "-".$maxBed;
            }
        }else{
            $bedroom = $minBed;
        }
        $area_unit = 'sq ft';
        
        $starting_price = 0; 
        $dateStr = $project->completion_date;
        $month = date("n", strtotime($dateStr));
        $yearQuarter = ceil($month / 3);
        
        view()->share([
            'project' => $project,
            'area_unit'=>$area_unit,
            'starting_price'=> count( $project->subProjects) > 0 ? $project->subProjects->where('starting_price', $project->subProjects->min('starting_price'))->first()->starting_price : 0,
            'bedrooms'=> $bedroom,
            'handOver' => "Q".$yearQuarter." ".date("Y", strtotime($dateStr)),
            'communityName'=> $project->mainCommunity ? $project->mainCommunity->name : '',
            
        ]);
        $pdf = PDF::loadView('pdf.projectBrochure');
       
        return $pdf->download($project->title.' Brochure.pdf');
        
        // $PDFHtml="";
        // $data = [];
        // $PDFHtml.= view('pdf.projectBrochure', compact([
        //             'project'
        //     ]))->render();
        
        // $pdf = PDF::loadHTML($PDFHtml);
        // return $pdf->download();
        
       // return view('pdf.brochure', compact('project'));
        
    }
    public function storeData(Request $request){
        dd($request->all);
    }
    public function home()
    {
        $pagemeta =  PageTag::where('page_name', Route::current()->getName())->first();
        return view('frontend.home', compact('pagemeta'));
    }
    public function offPlan()
    {
        $pagemeta =  PageTag::where('page_name', Route::current()->getName())->first();
        return view('frontend.offPlan', compact('pagemeta'));
    }
    public function properties()
    {
        $pagemeta =  PageTag::where('page_name', Route::current()->getName())->first();
        return view('frontend.properties', compact('pagemeta'));
    }
    public function propertiesDemoS(Request $request)
    {
        $pagemeta =  PageTag::where('page_name', Route::current()->getName())->first();

        $accomodation = Accommodation::active()->get();
        $category = Category::active()->get();
        $community = Community::get();
        $bedrooms = Property::select('bedrooms')->groupBy('bedrooms')->get();

        if (($request->ajax() || $request->isMethod('post'))) {
            $collection = Property::query();
            // if ($request->filled('status_id')) {
            if (isset($request->accommodation_id)) {
                $collection->where('accommodation_id', $request->accommodation_id);
            }
            if (isset($request->category)) {
                if($request->category == 'buy'){
                    $collection->orWhere('category_id', 2);
                    $collection->orWhere('category_id', 6);
                }elseif($request->category == 'sale'){
                    
                } elseif($request->category == 'rent'){
                    $collection->orWhere('category_id', 1);
                }elseif($request->category == 'offplan'){
                    $collection->orWhere('category_id', 2);
                }elseif($request->category == 'ready'){
                    $collection->orWhere('category_id', 6);
                }
               
                
               // $collection->where('category_id', $request->category_id);
            }
            
            if (isset($request->category_id)) {
                $collection->where('category_id', $request->category_id);
            }
            if (isset($request->community)) {
                $collection->where('community_id', $request->community);
            }
            if (isset($request->bedrooms)) {
                $collection->where('bedrooms', $request->bedrooms);
            }
            if (isset($request->minprice) || isset($request->maxprice)) {
                if(isset($request->minprice)){
                    $collection->where('price', '>' ,(int)$request->minprice);
                }elseif(isset($request->maxprice)){
                    $collection->where('price', '<' ,(int)$request->maxprice);
                }
                if(isset($request->minprice) && isset($request->maxprice)){
                    $collection->whereBetween('price', [(int)$request->minprice,(int)$request->maxprice]);
                }
            }
            if($request->coordinates){
                $allPolygons = $request->coordinates;
                $polygons = [];
                foreach ($allPolygons as $coordinates) {
                    $polygon = '((';

                    foreach ($coordinates as $coord) {
                        $polygon .= $coord['lng'] . ' ' . $coord['lat'] . ',';
                    }
                    $polygon = rtrim($polygon, ',') . ',' . $coordinates[0]['lng'] . ' ' . $coordinates[0]['lat'] . '))';
                    $polygons[] = $polygon;
                }
                $multiPolygonString = 'MULTIPOLYGON(' . implode(',', $polygons) . ')';
                $collection->whereRaw("ST_Within(Point(address_longitude, address_latitude), ST_GeomFromText(?))", [$multiPolygonString]);
            }
            $properties = $collection->with('accommodations','communities','category')->active()->get();
            foreach ($properties as $key => $value) {
                $value->setAttribute('lat',(double)$value->address_latitude);
                $value->setAttribute('lng',(double)$value->address_longitude);
            }
            $properties=$properties->toJson();
            return response()->json(['success' => true, 'html' => $properties])->header('Access-Control-Allow-Origin', '*');
        }else{
            $properties = Property::with('accommodations','communities','category')->active()->get()->toJson();
        }

        return view('frontend.propertiesDemo2', compact('properties','accomodation','pagemeta','category','community','bedrooms'));
    }
    public function propertiesDemo(Request $request)
    {
        $pagemeta =  PageTag::where('page_name', Route::current()->getName())->first();
        
        $accomodation = Accommodation::active()->get();
        $category = Category::active()->get();
        $community = Community::get();
        $bedrooms = Property::select('bedrooms')->groupBy('bedrooms')->get();
        
        if (($request->ajax() && $request->isMethod('post'))) { 
            $collection = Property::query();
            // if ($request->filled('status_id')) {
            if (isset($request->acc)) {
                $collection->where('accommodation_id', $request->acc);
            }
            if (isset($request->cat)) {
                $collection->where('category_id', $request->cat);
            }
            if (isset($request->community)) {
                $collection->where('community_id', $request->community);
            }
            if (isset($request->bedrooms)) {
                $collection->where('bedrooms', $request->bedrooms);
            }
            if (isset($request->minprice) || isset($request->maxprice)) {
                if(isset($request->minprice)){
                    $collection->where('price', '>' ,(int)$request->minprice);
                }elseif(isset($request->maxprice)){
                    $collection->where('price', '<' ,(int)$request->maxprice);
                }
                if(isset($request->minprice) && isset($request->maxprice)){
                    $collection->whereBetween('price', [(int)$request->minprice,(int)$request->maxprice]);
                }
            }
            $properties = $collection->with('accommodations','communities','category')->active()->get()->toJson();
            return response()->json(['success' => true, 'html' => $properties]);
        }else{
            $properties = Property::with('accommodations','communities','category')->active()->get()->toJson();
        }
        return view('frontend.propertiesDemo2', compact('properties','accomodation','pagemeta','category','community','bedrooms'));
    }
    public function buy(Request $request)
    {
        $pagemeta =  PageTag::where('page_name', Route::current()->getName())->first();
        
        $accomodation = Accommodation::active()->get();
        $category = Category::active()->get();
        $community = Community::get();
        $bedrooms = Property::select('bedrooms')->groupBy('bedrooms')->get();
        
        if (($request->ajax() && $request->isMethod('post'))) { 
            $collection = Property::query();
            $collection = $collection->where('category_id', 2);
            // if ($request->filled('status_id')) {
            if (isset($request->acc)) {
                $collection->where('accommodation_id', $request->acc);
            }
            if (isset($request->cat)) {
                $collection->where('category_id', $request->cat);
            }
            if (isset($request->community)) {
                $collection->where('community_id', $request->community);
            }
            if (isset($request->bedrooms)) {
                $collection->where('bedrooms', $request->bedrooms);
            }
            if (isset($request->minprice) || isset($request->maxprice)) {
                if(isset($request->minprice)){
                    $collection->where('price', '>' ,(int)$request->minprice);
                }elseif(isset($request->maxprice)){
                    $collection->where('price', '<' ,(int)$request->maxprice);
                }
                if(isset($request->minprice) && isset($request->maxprice)){
                    $collection->whereBetween('price', [(int)$request->minprice,(int)$request->maxprice]);
                }
            }
            $properties = $collection->with('accommodations','communities','category')->active()->get()->toJson();
            return response()->json(['success' => true, 'html' => $properties]);
        }else{
            $properties = Property::with('accommodations','communities','category')->where('category_id', 2)->active()->get()->toJson();
        }
        return view('frontend.propertiesDemo2', compact('properties','accomodation','pagemeta','category','community','bedrooms'));
    }
    public function rent(Request $request)
    {
       $pagemeta =  PageTag::where('page_name', Route::current()->getName())->first();
        
        $accomodation = Accommodation::active()->get();
        $category = Category::active()->get();
        $community = Community::get();
        $bedrooms = Property::select('bedrooms')->groupBy('bedrooms')->get();
        
        if (($request->ajax() && $request->isMethod('post'))) { 
            $collection = Property::query();
            $collection = $collection->where('category_id',1);
            // if ($request->filled('status_id')) {
            if (isset($request->acc)) {
                $collection->where('accommodation_id', $request->acc);
            }
            if (isset($request->cat)) {
                $collection->where('category_id', $request->cat);
            }
            if (isset($request->community)) {
                $collection->where('community_id', $request->community);
            }
            if (isset($request->bedrooms)) {
                $collection->where('bedrooms', $request->bedrooms);
            }
           if (isset($request->minprice) || isset($request->maxprice)) {
                 if(isset($request->minprice)){
                    $collection->where('price', '>' ,(int)$request->minprice);
                }elseif(isset($request->maxprice)){
                    $collection->where('price', '<' ,(int)$request->maxprice);
                }
                if(isset($request->minprice) && isset($request->maxprice)){
                    $collection->whereBetween('price', [(int)$request->minprice,(int)$request->maxprice]);
                }
            }
            $properties = $collection->with('accommodations','communities','category')->active()->get()->toJson();
            return response()->json(['success' => true, 'html' => $properties]);
        }else{
            $properties = Property::with('accommodations','communities','category')->where('category_id',1)->active()->get()->toJson();
        }
        return view('frontend.propertiesDemo2', compact('properties','accomodation','pagemeta','category','community','bedrooms'));
    }
    public function luxuryProperties()
    {
        $pagemeta =  PageTag::where('page_name', Route::current()->getName())->first();
        return view('frontend.properties', compact('pagemeta'));
    }
    public function singleProject()
    {
        return view('frontend.singleProject');
    }
    public function singlePropertyPage($slug)
    {
        if(Property::where('slug', $slug)->exists()){
            $property = Property::where('slug', $slug)->first();
            return view('frontend.singlePropertyPage', compact('property'));
        }
    }
    public function singleCommunity()
    {
        return view('frontend.singleCommunity');
    }
    public function singleCommunityPage($slug)
    {
        if(Community::where('slug', $slug)->exists()){
            $community = Community::where('slug', $slug)->first();
            return view('frontend.singleCommunityPage', compact('community'));
        }
    }
    public function singleDeveloper()
    {
        return view('frontend.singleDeveloper');
    }
    public function singleDeveloperPage($slug)
    {
        if(Developer::where('slug', $slug)->exists()){
            $developer = Developer::where('slug', $slug)->first();
            return view('frontend.singleDeveloperPage', compact('developer'));
        }
    }
    public function communities()
    {
        $pagemeta =  PageTag::where('page_name', Route::current()->getName())->first();
        return view('frontend.communities', compact('pagemeta'));
    }
    public function aboutUs()
    {
        $pagemeta =  PageTag::where('page_name', Route::current()->getName())->first();

        return view('frontend.aboutUs', compact('pagemeta'));
    }

    public function contact()
    {
        $pagemeta =  PageTag::where('page_name', Route::current()->getName())->first();
        return view('frontend.contact', compact('pagemeta'));
    }

    public function termsConditions()
    {
        $pagemeta =  PageTag::where('page_name', Route::current()->getName())->first();
        return view('frontend.termsConditions', compact('pagemeta'));
    }
    public function privacyPolicy()
    {
        // dd(Route::current()->getName());
        $pagemeta =  PageTag::where('page_name', Route::current()->getName())->first();
        return view('frontend.privacyPolicy', compact('pagemeta'));
    }

    public function thankYou()
    {
        $pagemeta =  PageTag::where('page_name', Route::current()->getName())->first();
        return view('frontend.thankYou', compact('pagemeta'));
    }
}
