<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\CommunityRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Str;
use App\Models\{
    Amenity,
    Accommodation,
    Category,
    CompletionStatus,
    Community,
    CommunityProximities,
    Subcommunity,
    Developer,
    OfferType,
    TagCategory,
    Stat,
    Highlight,
    Project

};
use Auth;
use DB;

class CommunityController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.real_estate'),
        ['only' => ['index','create', 'store','show','edit', 'update', 'destroy', 'subCommunities', 'mediaDestroy', 'mediasDestroy']
        ]);
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
        
        $collection = Community::with(['user' => function ($query) {
                                return $query->select('id', 'name');
                            }]);
                        
        if (isset($request->status)) {
            $collection->where('status', $request->status);
        }
        if (isset($request->display_on_home)) {
            $collection->where('display_on_home', $request->display_on_home);
        }
        if(isset($request->developer_ids)){
            $developer_ids =  $request->developer_ids;
            
            $collection->whereHas('developers', function ($query) use ($developer_ids) {
                $query->whereIn('developers.id', $developer_ids);
            });
            
        }
        if(isset($request->is_approved)){
             $collection->where('is_approved', $request->is_approved);
        }
        
        if (isset($request->keyword)) {
            $keyword = $request->keyword;
            $collection->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%");
            });
        }
        
        if(isset($request->orderby)){
            $orderBy = $request->input('orderby', 'created_at'); // default_column is the default field to sort by
            $direction = $request->input('direction', 'asc'); // Default sorting direction
            $communities = $collection->orderByRaw('ISNULL(communityOrder)')->orderBy($orderBy, $direction)->paginate($current_page);

        }else{
            $communities = $collection->latest()->paginate($current_page);
        }
        
        
       // $communities = $collection->latest()->paginate($current_page);
        $developers = Developer::latest()->pluck('name', 'id');
        
        return view('dashboard.realEstate.communities.index', compact(
            'communities',
            'developers',
            'sr_no_start',
            'current_page'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $amenities = Amenity::active()->approved()->latest()->get();
        $highlights = Highlight::active()->approved()->latest()->get();
        //$categories = Category::active()->latest()->get();
        // $tags = TagCategory::active()->latest()->get();
        $developers = Developer::active()->approved()->latest()->get();
        //$communities = Community::active()->latest()->get();

        return view('dashboard.realEstate.communities.create', compact('developers','amenities', 'highlights'));
    }
    public function mainImage(){
       
        foreach(Community::latest()->get() as $communnity){
            $communnity->listing_image = $communnity->listMainImage;
            $communnity->save();
            echo "communnity-".$communnity->id;
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommunityRequest $request)
    {
        try{
            $community = new Community;
            $community->name = $request->name;
            $community->status = $request->status;
            $community->communityOrder = $request->communityOrder;
            $community->emirates = $request->emirates;
            //$community->short_description = $request->short_description;
            $community->shortDescription = $request->shortDescription;
            $community->description = $request->description;
            $community->meta_title = $request->meta_title;
            $community->meta_description = $request->meta_description;
            $community->meta_keywords = $request->meta_keywords;
            $community->location_iframe = $request->location_iframe;
            $community->display_on_home = $request->display_on_home;
            $community->address = $request->address;
            $community->address_latitude = $request->address_latitude;
            $community->address_longitude = $request->address_longitude;
            if(in_array(Auth::user()->role, config('constants.isAdmin'))){
                $community->is_approved = config('constants.approved' );
                $community->approval_id = Auth::user()->id;
                
            }else{
                $community->is_approved = config('constants.requested' );
            }

            if ($request->hasFile('mainImage')) {
                $img =  $request->file('mainImage');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name).'.'.$ext;
                $community->addMediaFromRequest('mainImage')->usingFileName($imageName)->toMediaCollection('mainImages', 'commnityFiles');
            }
            
            if ($request->hasFile('listMainImage')) {
                $img =  $request->file('listMainImage');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name).'.'.$ext;
                $community->addMediaFromRequest('listMainImage')->usingFileName($imageName)->toMediaCollection('listMainImages', 'commnityFiles');
            }
            
            
            if ($request->hasFile('clusterPlan')) {
              
                $img =  $request->file('clusterPlan');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name).'.'.$ext;
                $community->addMediaFromRequest('clusterPlan')->usingFileName($imageName)->toMediaCollection('clusterPlans', 'commnityFiles' );
            }

            // if ($request->hasFile('imageGallery')) {
            //     $subImages = $request->file('imageGallery');
                
            //     foreach ($subImages as $subImage) {
            //         $community->addMedia($subImage)->toMediaCollection('imageGalleries', 'commnityFiles');
            //     }
            // }
            
            if ($request->has('gallery')) {
                foreach($request->gallery as $key=>$img)
                {
                    if(array_key_exists("file", $img) && $img['file']){
                        $title = $img['title'] ?? $request->name;
                        $order =  $img['order'] ?? null;
        
                        $community->addMedia( $img['file'])
                                ->withCustomProperties([
                                    'title' => $title,
                                    'order' => $order
                                ])
                            ->toMediaCollection('imageGalleries', 'commnityFiles');   
                    }
                }
                
            }
            

            if ($request->hasFile('video')) {
                $video =  $request->file('video');
                $ext = $video->getClientOriginalExtension();
                $videoName =  Str::slug($request->name).'.'.$ext;
                $community->addMediaFromRequest('video')->usingFileName($videoName)->toMediaCollection('videos', 'commnityFiles');
            }

            $community->user_id = Auth::user()->id;
            //$community->parent_id = $request->parent_id;
            // if($request->parant_id){
            //     $parentCommunity = Community::find($request->parant_id);
            //     $community->search_keyword = $request->name ."(". $parentCommunity->name.", ".$request->emirates.")";
            // }else{
            //      $community->search_keyword = $request->name."(".$request->emirates.")";
            // }
            
            $community->updated_by = Auth::user()->id;
            
            $community->save();
            
            $community->banner_image = $community->mainImage;
            $community->listing_image = $community->listMainImage;
            $community->save();
            if($request->has('categoryIds')){
                $community->categories()->attach($request->categoryIds);
            }
            if($request->has('tagIds')){
                foreach($request->tagIds as $tag){
                    $community->tags()->create(['tag_category_id'=>$tag]);
                }
            }
            if($request->has('developerIds')){
                $community->communityDevelopers()->attach($request->developerIds);
            }
            if($request->has('amenityIds')){
                $community->amenities()->attach($request->amenityIds);
            }
            if($request->has('highlightIds')){
                $community->highlights()->attach($request->highlightIds);
            }

            return response()->json([
                'success' => true,
                'message'=> 'Community has been created successfully.',
                'redirect' => route('dashboard.communities.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.communities.index'),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Community $community)
    {
        
        return redirect()->route('community', $community->slug);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $community)
    {
        $community = Community::with('amenities', 'highlights')->find( $community);
       
        $amenities = DB::table('amenities')->select('id', 'name')->get();
        $developers = DB::table('developers')->select('id', 'name')->get();
        $highlights = DB::table('highlights')->select('id', 'name')->get();
        
        // $amenities = [];
        // $developers =  [];
        // $highlights =  [];
        
    //   return response()->json([
    //       'developers'=>$devlopers,
    //       'community'=>$community,
    //       'amenities'=>$amenities, 
    //       'highlights'=>$highlights
    //       ]);
        return view('dashboard.realEstate.communities.edit',compact('developers','community','amenities', 'highlights'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CommunityRequest $request, Community $community)
    {
        DB::beginTransaction();
        try{
            
             $community->name = $request->name;
            $community->status = $request->status;
            $community->communityOrder = $request->communityOrder;
            $community->emirates = $request->emirates;
          
            $community->shortDescription = $request->shortDescription;
            $community->description = $request->description;
            $community->meta_title = $request->meta_title;
            $community->meta_description = $request->meta_description;
            $community->meta_keywords = $request->meta_keywords;
            $community->location_iframe = $request->location_iframe;
            $community->display_on_home = $request->display_on_home;
            $community->address = $request->address;
            $community->address_latitude = $request->address_latitude;
            $community->address_longitude = $request->address_longitude;
            
            if ($request->hasFile('mainImage')) {
                $community->clearMediaCollection('mainImages');
                $img =  $request->file('mainImage');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name).'.'.$ext;
                $community->addMediaFromRequest('mainImage')->usingFileName($imageName)->toMediaCollection('mainImages', 'commnityFiles');
            }
            
            
            // if ($request->hasFile('listMainImage')) {
            //     $community->clearMediaCollection('listMainImages');
                
            //     $img =  $request->file('listMainImage');
            //     $ext = $img->getClientOriginalExtension();
            //     $imageName =  Str::slug($request->name).'.'.$ext;
            //     $community->addMediaFromRequest('listMainImage')->usingFileName($imageName)->toMediaCollection('listMainImages', 'commnityFiles');
            // }
            if ($request->hasFile('clusterPlan')) {
               
                $community->clearMediaCollection('clusterPlans');
                $img =  $request->file('clusterPlan');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->name).'.'.$ext;
                $community->addMediaFromRequest('clusterPlan')->usingFileName($imageName)->toMediaCollection('clusterPlans', 'commnityFiles' );
            }

            // if ($request->hasFile('imageGallery')) {
            //     $subImages = $request->file('imageGallery');

            //     foreach ($subImages as $subImage) {
            //         $community->addMedia($subImage)->toMediaCollection('imageGalleries', 'commnityFiles');
            //     }
            // }
            
            
            if ($request->has('gallery')) {
                foreach ($request->gallery as $img) {
                        $title = $img['title'] ?? $request->name;
                        $order =  $img['order'] ?? null;
                        
                        if ($img['old_gallery_id'] > 0) {
                            
                            $mediaItem = Media::find($img['old_gallery_id']);
                            $mediaItem->setCustomProperty('title', $title);
                            $mediaItem->setCustomProperty('order', $order);
                            $mediaItem->save();
                            
                        } else {
                            if(array_key_exists("file", $img) && $img['file']){
                                $community->addMedia( $img['file'])
                                ->withCustomProperties([
                                    'title' => $title,
                                    'order' => $order
                                ])
                                ->toMediaCollection('imageGalleries', 'commnityFiles');
                        }
                    }
                }
            }
            

          

            $community->user_id = Auth::user()->id;

            if(in_array(Auth::user()->role, config('constants.isAdmin'))){
                $community->approval_id = Auth::user()->id;
                
                if(in_array($request->is_approved, ["approved", "rejected"]) ){
                    $community->is_approved = $request->is_approved;
                }
            }else{
                $community->is_approved = "requested";
                $community->approval_id = null;
            }
            $community->updated_by = Auth::user()->id;
           
            $community->save();
            $community->banner_image = $community->mainImage;
          
          
            
            $community->save();


            if($request->has('developerIds')){
                $community->communityDevelopers()->detach();
                $community->communityDevelopers()->attach($request->developerIds);
            }else{
                $community->communityDevelopers()->detach();
            }

      
            if($request->has('amenityIds')){
                $community->amenities()->detach();
                $community->amenities()->attach($request->amenityIds);
            }else{
                $community->amenities()->detach();
            }
            
            if($request->has('highlightIds')){
                $community->highlights()->detach();
                $community->highlights()->attach($request->highlightIds);
            }else{
                $community->highlights()->detach();
            }
            
             Project::where('community_id', $community->id)->update(['updated_brochure' => 0]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message'=> 'Community has been updated successfully.',
                'redirect' => route('dashboard.communities.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.communities.index'),
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Community $community)
    {
        try{
            $community->tags()->delete();
            $community->delete();

            return redirect()->route('dashboard.communities.index')->with('success','Communicaty has been deleted successfully');
        }catch(\Exception $error){
            return redirect()->route('dashboard.communities.index')->with('error',$error->getMessage());
        }

    }
    public function subCommunities(Request $request)
    {
        $parent_id = $request->category_id;
        $subcategories = Community::where('parent_id', $parent_id)->select('id', 'name')->latest()->get();
        return response()->json([
            'subcategories' => $subcategories
        ]);
    }
    
    public function developers(Request $request)
    {
        $community_id = $request->category_id;
        $developers = Developer::whereHas('communities', function($q) use ($community_id){
            $q->where('communities.id', $community_id );
        })->select('id', 'name')->latest()->get();
        return response()->json([
            'developers' => $developers
        ]);
    }
    
    public function projects(Request $request)
    {
        $community_id = $request->category_id;
        $projects = Project::where('community_id', $community_id)->select('id', 'title')->latest()->get();
        return response()->json([
            'projects' => $projects
        ]);
    }
    
    
    
    public function developerCommunities(Request $request)
    {
        $developer_id = $request->developer_id;
        $communities = Community::main()->select('id', 'name')->whereHas('developers', function ($query) use ($developer_id) {
                $query->where('developers.id', $developer_id);
            })->select('id', 'name', 'status')->latest()->get();
        return response()->json([
            'communities' => $communities
        ]);
    }
    
    public function mediaDestroy(Community $community, $media)
    {
        try{
            $community->deleteMedia($media);
            return redirect()->route('dashboard.communities.edit', $community->id)->with('success','Community Image has been deleted successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.communities.edit', $community->id)->with('error',$error->getMessage());
        }
    }
    public function mediasDestroy(Community $community)
    {
        try{
            $community->clearMediaCollection('imageGalleries');
            return redirect()->route('dashboard.communities.edit', $community->id)->with('success','Community Gallery has been deleted successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.communities.edit', $community->id)->with('error',$error->getMessage());
        }
    }
}
