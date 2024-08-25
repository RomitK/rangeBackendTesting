<?php

namespace App\Http\Controllers\Dashboard\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\InventoryRequest;
use App\Repositories\Contracts\InventoryRepositoryInterface;
use Carbon\Carbon;
use App\Exceptions\InventoryException;
use Illuminate\Support\Str;
use App\Models\{
    Community,
    Developer,
    Project,
    Accommodation
};

use  App\Imports\{
    InventoryImport
};
use Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Dashboard\Project\{
    ProjectRequest,
    SubProjectRequest,
    ProjectPaymentRequest,
};
use App\Repositories\Contracts\ProjectRepositoryInterface;

class InventoryReport extends Controller
{
    protected $inventoryRepository;

    function __construct(InventoryRepositoryInterface $inventoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
    }
    public function index(Request $request)
    {
        $result = $this->inventoryRepository->filterData($request);

        if ($request->has('export')) {
            // Handle export scenario
            return $result; // This will return the JSON response from your repository method
        } else {
            // Handle normal pagination scenario
            $projects = $result['projects'];
            $current_page = $result['current_page'];
            $sr_no_start = $result['sr_no_start'];

            $communities = Community::latest()->pluck('name', 'id');
            $communities->prepend('All', '');

            $developers = Developer::latest()->pluck('name', 'id');
            $developers->prepend('All', '');



            return view('dashboard.realEstate.inventory.index', compact(
                'projects',
                'sr_no_start',
                'communities',
                'current_page',
                'developers',

            ));
        }
    }
    public function inventoryList(Project $project)
    {
        $today = Carbon::now();

        $project = $project->withCount([
            'properties as available_count' => function ($query) {
                $query->where('properties.website_status', config('constants.available'))->where('properties.property_source', 'crm');
            },
            'properties as na_count' => function ($query) {
                $query->where('properties.website_status', config('constants.NA'))->where('properties.property_source', 'crm');
            },
            'properties as requested_count' => function ($query) {
                $query->where('properties.website_status', config('constants.requested'))->where('properties.property_source', 'crm');
            },
            'properties as rejected_count' => function ($query) {
                $query->where('properties.website_status', config('constants.rejected'))->where('properties.property_source', 'crm');
            }
        ])
            ->where('projects.id', $project->id)
            ->with('user')
            ->selectRaw('projects.*, DATEDIFF(?, (SELECT MAX(updated_at) FROM properties WHERE properties.project_id = projects.id)) as date_diff', [$today])
            ->first();

        return view('dashboard.reports.inventory.inventory', compact(
            'project',
        ));
    }
    public function inventoryUpdate2(InventoryRequest $request, Project $project)
    {
        try {
            if ($request->hasFile('inventoryFile')) {
                $file = $request->file('inventoryFile');

                // Check if the file is valid
                if ($file->isValid()) {

                    Excel::import(new InventoryImport($project), $file);

                    $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
                    $ext = $file->getClientOriginalExtension();
                    $imageName = $timestamp . '.' . $ext;

                    // Log the file details
                    Log::info('Uploading file', ['file_name' => $imageName, 'mime_type' => $file->getMimeType(), 'size' => $file->getSize()]);

                    $project->addMedia($file)
                        ->usingFileName($imageName)
                        ->withCustomProperties(['uploaded_by' => Auth::user()->id])
                        ->toMediaCollection('inventoryFiles', 'projectFiles');

                    $project->save();

                    // Perform the import


                    return response()->json([
                        'import' => true,
                        'success' => true,
                        'message' => 'File imported successfully',
                        'redirect' => route('dashboard.projects.inventoryList', $project->id),
                    ]);
                } else {
                    throw new \Exception('Uploaded file is not valid.');
                }
            } else {
                throw new \Exception('No file was uploaded.');
            }
        } catch (InventoryException $e) {
            return response()->json([
                'errors' => [
                    'code' => $e->getErrorCode(),
                    'message' => $e->getMessage(),
                ]
            ], $e->getErrorCode());
        } catch (\Exception $e) {
            return response()->json([
                'error' => [
                    'code' => 500,
                    'message' => 'An unexpected error occurred: ' . $e->getMessage(),
                ]
            ], 500);
        }
    }
}
