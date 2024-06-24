<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Dashboard\{
    DeveloperRequest,
    DeveloperMetaRequest
};
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Str;
use App\Models\{
    Developer,
    TagCategory,
    MetaDetail,
    Project,
    LogActivity
};
use App\Jobs\{
    DeveloperExportAndEmailData,
    DeveloperLogExportAndEmailData
};
use Carbon\Carbon;
use App\Repositories\Contracts\DeveloperRepositoryInterface;

class DeveloperController extends Controller
{
    protected $developerRepository;

    function __construct(DeveloperRepositoryInterface $developerRepository)
    {
        $this->middleware(function ($request, $next) {
            // Check if the user has the "real_estate" permission
            if (Auth::user()->hasPermissionTo(config('constants.Permissions.real_estate'))) {
                // Apply middleware only for these actions
                $this->middleware('permission:' . config('constants.Permissions.real_estate'))->only([
                    'index', 'create', 'edit', 'update', 'destroy',
                    'details', 'createDetail', 'storeDetail', 'editDetail', 'updateDetail', 'destroyDetail'
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

        $this->developerRepository = $developerRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = $this->developerRepository->filterData($request);

        if ($request->has('export')) {
            // Handle export scenario
            return $result; // This will return the JSON response from your repository method
        } else {
            // Handle normal pagination scenario
            $developers = $result['developers'];
            $current_page = $result['current_page'];
            $sr_no_start = $result['sr_no_start'];

            return view('dashboard.realEstate.developers.index', compact(
                'developers',
                'current_page',
                'sr_no_start'
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
        return view('dashboard.realEstate.developers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeveloperRequest $request)
    {
        try {

            $result = $this->developerRepository->storeData($request);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'redirect' => route('dashboard.developers.index'),
                'developer_id' => $result['developer_id'], // If returned from repository
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.developers.index'),
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
    public function logs(Developer $developer, Request $request)
    {
        if (isset($request->export)) {
            $data = [
                'email' => Auth::user()->email,
                'userName' => Auth::user()->name,
                'developer_id' => $developer->id, // Example of passing the developer ID
            ];

            DeveloperLogExportAndEmailData::dispatch($data, $developer);

            return redirect()->route('dashboard.developers.logs', $developer->id)->with('success', 'Please Check Email, Log History has been sent');
        } else {
            return view('dashboard.realEstate.developers.details.index', compact('developer'));
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Developer $developer)
    {
        return view('dashboard.realEstate.developers.edit', compact('developer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DeveloperRequest $request, Developer $developer)
    {
        try {
            $result = $this->developerRepository->updateData($request, $developer);
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'redirect' => route('dashboard.developers.index'),
                // 'developer_id' => $result['developer_id'], // If returned from repository
            ]);
        } catch (\Exception $error) {

            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.developers.index'),
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Developer $developer)
    {
        try {
            $developer->delete();

            return redirect()->route('dashboard.developers.index')->with('success', 'Developer has been deleted successfully');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.developers.index')->with('error', $error->getMessage());
        }
    }

    public function mediaDestroy(Developer $developer, $media)
    {
        try {
            $developer->deleteMedia($media);
            return redirect()->route('dashboard.developers.edit', $developer->id)->with('success', 'Developer Image has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.developers.edit', $developer->id)->with('error', $error->getMessage());
        }
    }
    public function mediasDestroy(Developer $developer)
    {
        try {
            $developer->clearMediaCollection('gallery');
            return redirect()->route('dashboard.developers.edit', $developer->id)->with('success', 'Developer Gallery has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.developers.edit', $developer->id)->with('error', $error->getMessage());
        }
    }
    public function meta(Developer $developer)
    {
        return view('dashboard.realEstate.developers.meta', compact('developer'));
    }
    public function updateMeta(DeveloperMetaRequest $request, Developer $developer)
    {
        try {
            $result = $this->developerRepository->updateMetaData($request, $developer);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'redirect' => route('dashboard.developers.index'),
                'developer_id' => $result['developer_id'], // If returned from repository
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route('dashboard.developers.index'),
            ]);
        }
    }

    public function details(Developer $developer)
    {
        return view('dashboard.realEstate.developers.details.index', compact('developer'));
    }
    public function createDetail(Developer $developer)
    {
        return view('dashboard.realEstate.developers.details.create', compact('developer'));
    }
    public function storeDetail(Request $request, Developer $developer)
    {
        DB::beginTransaction();
        try {
            $specification = $developer->details()->create(['name' => $request->name, 'value' => $request->value]);
            DB::commit();
            return redirect()->route('dashboard.developer.details', [$developer->id])->with('success', 'Developer Detail has been created successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.developer.details', [$developer->id])->with('error', $error->getMessage());
        }
    }
    public function editDetail(Developer $developer, MetaDetail $detail)
    {
        return view('dashboard.realEstate.developers.details.edit', compact('developer', 'detail'));
    }
    public function updateDetail(Request $request, Developer $developer, MetaDetail $detail)
    {
        DB::beginTransaction();
        try {
            $detail->name = $request->name;
            $detail->value = $request->value;

            $detail->save();
            DB::commit();
            return redirect()->route('dashboard.developer.details', [$developer->id])->with('success', 'Developer Detail has been updated successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.developer.details', [$developer->id])->with('error', $error->getMessage());
        }
    }
    public function destroyDetail(Developer $developer, MetaDetail $detail)
    {
        DB::beginTransaction();
        try {
            $detail->delete();
            DB::commit();
            return redirect()->route('dashboard.developer.details', [$developer->id])->with('success', 'Developer Detail has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.developer.details', [$developer->id])->with('error', $error->getMessage());
        }
    }
}
