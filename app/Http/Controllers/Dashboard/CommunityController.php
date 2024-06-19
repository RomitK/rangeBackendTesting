<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\{
    CommunityRequest,
    CommunityMetaRequest
};
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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Jobs\{
    CommunityExportAndEmailData,
    CommunityLogExportAndEmailData
};
use App\Repositories\Contracts\CommunityRepositoryInterface;

class CommunityController extends Controller
{
    protected $communityRepository;

    function __construct(CommunityRepositoryInterface $communityRepository)
    {

        $this->middleware(function ($request, $next) {
            // Check if the user has the "real_estate" permission
            if (Auth::user()->hasPermissionTo(config('constants.Permissions.real_estate'))) {
                // Apply middleware only for these actions
                $this->middleware('permission:' . config('constants.Permissions.real_estate'))->only([
                    'index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'subCommunities', 'mediaDestroy', 'mediasDestroy'
                ]);
            }

            // Check if the user has the "seo" permission
            if (Auth::user()->hasPermissionTo(config('constants.Permissions.seo'))) {
                // Apply middleware only for these actions
                $this->middleware('permission:' . config('constants.Permissions.seo'))->only([
                    'updateMeta', 'index'
                ]);
            }

            return $next($request);
        });
        $this->communityRepository = $communityRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        $result = $this->communityRepository->filterData($request);

        if ($request->has('export')) {
            // Handle export scenario
            return $result; // This will return the JSON response from your repository method
        } else {
            // Handle normal pagination scenario
            $communities = $result['communities'];
            $current_page = $result['current_page'];
            $sr_no_start = $result['sr_no_start'];

            $developers = Developer::latest()->pluck('name', 'id');


            return view('dashboard.realEstate.communities.index', compact(
                'communities',
                'current_page',
                'sr_no_start',
                'developers'
            ));
        }
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

        return view('dashboard.realEstate.communities.create', compact('developers', 'amenities', 'highlights'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommunityRequest $request)
    {
        try {
            $result = $this->communityRepository->storeData($request);
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'redirect' => route('dashboard.communities.index'),
                'developer_id' => $result['developer_id'], // If returned from repository
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
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
    public function logs(Community $community, Request $request)
    {

        if (isset($request->export)) {
            $data = [
                'email' => Auth::user()->email,
                'userName' => Auth::user()->name,
                'developer_id' => $community->id, // Example of passing the developer ID
            ];

            CommunityLogExportAndEmailData::dispatch($data, $community);

            return redirect()->route('dashboard.communities.logs', $community->id)->with('success', 'Please Check Email, Log History has been sent');
        } else {
            return view('dashboard.realEstate.communities.logs.index', compact('community'));
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($community)
    {

        $community = Community::with('amenities', 'highlights', 'communityDevelopers')->find($community);

        $amenities = DB::table('amenities')->select('id', 'name')->whereNull('deleted_at')->get();
        $developers = DB::table('developers')->select('id', 'name')->whereNull('deleted_at')->get();
        $highlights = DB::table('highlights')->select('id', 'name')->whereNull('deleted_at')->get();


        return view('dashboard.realEstate.communities.edit', compact('developers', 'community', 'amenities', 'highlights'));
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
        try {
            $result = $this->communityRepository->updateData($request, $community);
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'redirect' => route('dashboard.communities.index'),
                'community_id' => $result['community_id'], // If returned from repository
            ]);
        } catch (\Exception $error) {

            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
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
        try {
            $community->tags()->delete();
            $community->delete();

            return redirect()->route('dashboard.communities.index')->with('success', 'Communicaty has been deleted successfully');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.communities.index')->with('error', $error->getMessage());
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
        $developers = Developer::whereHas('communities', function ($q) use ($community_id) {
            $q->where('communities.id', $community_id);
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
        try {
            $community->deleteMedia($media);
            return redirect()->route('dashboard.communities.edit', $community->id)->with('success', 'Community Image has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.communities.edit', $community->id)->with('error', $error->getMessage());
        }
    }
    public function mediasDestroy(Community $community)
    {
        try {
            $community->clearMediaCollection('imageGalleries');
            return redirect()->route('dashboard.communities.edit', $community->id)->with('success', 'Community Gallery has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.communities.edit', $community->id)->with('error', $error->getMessage());
        }
    }

    public function meta(Community $community)
    {
        return view('dashboard.realEstate.communities.meta', compact('community'));
    }
    public function updateMeta(CommunityMetaRequest $request, Community $community)
    {
        try {
            $result = $this->communityRepository->updateMetaData($request, $community);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'redirect' => route('dashboard.communities.index'),
                'community_id' => $result['community_id'], // If returned from repository
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.communities.index'),
            ]);
        }
    }
}
