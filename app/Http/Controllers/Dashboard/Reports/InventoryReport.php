<?php

namespace App\Http\Controllers\Dashboard\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\InventoryRequest;
use App\Repositories\Contracts\InventoryRepositoryInterface;
use Carbon\Carbon;
use App\Exceptions\InventoryException;
use App\Models\{
    Community,
    Developer,
    Project
};

use  App\Imports\{
    InventoryImport
};
use Excel;

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



            return view('dashboard.reports.inventory.index', compact(
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
                $query->where('properties.website_status', config('constants.available'));
            },
            'properties as na_count' => function ($query) {
                $query->where('properties.website_status', config('constants.NA'));
            },
            'properties as requested_count' => function ($query) {
                $query->where('properties.website_status', config('constants.requested'));
            },
            'properties as rejected_count' => function ($query) {
                $query->where('properties.website_status', config('constants.rejected'));
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
    public function  inventoryUpdate(InventoryRequest $request, Project $project)
    {
        try {
            if ($request->hasFile('inventoryFile')) {
                $file = $request->file('inventoryFile');
                Excel::import(new InventoryImport, $file);
            }
            return response()->json(['message' => 'File imported successfully'], 200);
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
                    'message' => 'An unexpected error occurred',
                ]
            ], 500);
        }
    }
}
