<?php

namespace App\Http\Controllers\Dashboard\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{
    Project
};

class ProjectReport extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.reports.projects.index');
    }
    public function ajaxProjectReport(Request $request)
    {
        try {
            $colorMappingStatus = [
                'active' => '#28a745', // Green
                'inactive' => '#dc3545', // Red
            ];

            $colorMappingApproval = [
                'approved' => '#007bff', // Blue
                'requested' => '#ffc107', // Yellow
                'rejected' => '#6c757d', // Gray
            ];

            $collection = Project::mainProject();

            if (isset($request->startDate) && isset($request->endDate)) {
                $startDate = Carbon::parse($request->startDate);
                $endDate = Carbon::parse($request->endDate);
                $collection = $collection->whereBetween('created_at', [$startDate, $endDate]);
            }

            $unitPropertiseWiseCollection = clone $collection;
            $approvalDataCollection = clone $collection;
            $permitWiseCollection = clone $collection;


            // Status-wise data
            $statusData = $collection->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get()
                ->map(function ($item) use ($colorMappingStatus) {
                    return [
                        'status' => $item->status,
                        'count' => $item->count,
                        'color' => $colorMappingStatus[strtolower($item->status)] ?? '#000000', // Default to black if color not found
                    ];
                })
                ->toArray();


            // Approval-wise data
            $approvalData = $approvalDataCollection->selectRaw('is_approved, COUNT(*) as count')
                ->groupBy('is_approved')
                ->get()
                ->map(function ($item) use ($colorMappingApproval) {
                    return [
                        'status' => $item->is_approved,
                        'count' => $item->count,
                        'color' => $colorMappingApproval[strtolower($item->is_approved)] ?? '#000000', // Default to black if color not found
                    ];
                })
                ->toArray();



            $permitCollection = $permitWiseCollection->get();

            $permitWiseCollection = $permitCollection->whereNotNull('permit_number');

            $notpermitWiseCollection = $permitCollection->whereNull('permit_number');

            // Permit number-wise data
            $permitData = [
                [
                    'status' => 'With Permit Number',
                    'count' => count($permitWiseCollection->all()),
                    'color' => '#17a2b8', // Info
                ],
                [
                    'status' => 'Without Permit Number',
                    'count' => count($notpermitWiseCollection->all()),
                    'color' => '#6c757d', // Secondary
                ]
            ];

            // Fetch projects with project and property counts
            $propertiseWiseData = $unitPropertiseWiseCollection->with(['properties', 'subProjects'])
                ->get()
                ->map(function ($project) {
                    return [
                        'name' => $project->title,
                        'units' => $project->subProjects->count(),
                        'properties' => $project->properties->count(),
                    ];
                })
                ->toArray();

            $data = [
                'statusWiseData' => $statusData,
                'approvalWiseData' => $approvalData,
                'permitWiseData' => $permitData,
                'propertiseWiseData' => $propertiseWiseData
            ];

            return $this->success('Project Report', $data, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
}
