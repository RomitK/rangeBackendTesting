<?php

namespace App\Http\Controllers\Dashboard\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    Project,
    Property
};
use App\Jobs\{
    CommunityReportAndEmailData
};
use Carbon\Carbon;

class CommunityReport extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.reports.communities.index');
    }
    public function ajaxCommunityReportData(Request $request)
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

            $collection = Community::query();

            if (isset($request->startDate) && isset($request->endDate)) {
                $startDate = Carbon::parse($request->startDate);
                $endDate = Carbon::parse($request->endDate);
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

            // Fetch communities with project and property counts
            $projectPropertiseWiseData = $projectPropertiseWiseCollection->with(['projects', 'projects.properties'])
                ->get()
                ->map(function ($community) {
                    $projectCount = $community->projects->count();
                    $propertyCount = $community->projects->sum(function ($project) {
                        return $project->properties->count();
                    });
                    return [
                        'name' => $community->name,
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
                CommunityReportAndEmailData::dispatch($data);

                return response()->json([
                    'success' => true,
                    'message' => 'Please Check Email, Report has been sent.',
                ]);
            }
            return $this->success('Communities Report', $data, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
}
