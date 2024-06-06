<?php

namespace App\Http\Controllers\Dashboard\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{
    Developer
};
use App\Jobs\{
    DeveloperReportAndEmailData
};

class DeveloperReport extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.reports.developers.index');
    }
    public function ajaxDeveloperReport(Request $request)
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

            $collection = Developer::query();

            if (isset($request->startDate) && isset($request->endDate)) {
                $startDate = Carbon::parse($request->startDate);
                $endDate = Carbon::parse($request->endDate)->endOfDay();
                $collection = $collection->whereBetween('created_at', [$startDate, $endDate]);
            }

            $projectPropertiseWiseCollection = clone $collection;

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
            $approvalData = $collection->selectRaw('is_approved, COUNT(*) as count')
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

            // Fetch developers with project and property counts
            $projectPropertiseWiseData = $projectPropertiseWiseCollection->with(['projects', 'projects.properties'])
                ->get()
                ->map(function ($developer) {
                    $projectCount = $developer->projects->count();
                    $propertyCount = $developer->projects->sum(function ($project) {
                        return $project->properties->count();
                    });
                    return [
                        'name' => $developer->name,
                        'projects' => $projectCount,
                        'properties' => $propertyCount,
                    ];
                })->toArray();


            $data = [
                'statusWiseData' => $statusData,
                'approvalWiseData' => $approvalData,
                'projectPropertiseWiseData' => $projectPropertiseWiseData
            ];


            if (isset($request->download) && $request->download == 1) {
                $data = [
                    'startDate' => $startDate->format('d m Y'),
                    'endDate' => $endDate->format('d m Y'),
                    'email' => Auth::user()->email,
                    'userName' => Auth::user()->name,
                    'statusWiseData' => $statusData,
                    'approvalWiseData' => $approvalData,
                    'projectPropertiseWiseData' => $projectPropertiseWiseData
                ];
                DeveloperReportAndEmailData::dispatch($data);

                return response()->json([
                    'success' => true,
                    'message' => 'Please Check Email, Report has been sent.',
                ]);
            }


            return $this->success('Developer Report', $data, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
}
