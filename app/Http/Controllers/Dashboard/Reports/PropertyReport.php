<?php

namespace App\Http\Controllers\Dashboard\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{
    Property
};

class PropertyReport extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.reports.properties.index');
    }
    public function ajaxPropertyReport(Request $request)
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

            $collection = Property::query();

            if (isset($request->startDate) && isset($request->endDate)) {
                $startDate = Carbon::parse($request->startDate);
                $endDate = Carbon::parse($request->endDate);
                $collection = $collection->whereBetween('properties.created_at', [$startDate, $endDate]);
            }

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



            $permitCollection = $permitWiseCollection->join('projects', 'properties.project_id', '=', 'projects.id')->selectRaw('
            COUNT(CASE WHEN projects.permit_number IS NOT NULL THEN 1 END) as with_permit_number,
            COUNT(CASE WHEN projects.permit_number IS NULL THEN 1 END) as without_permit_number
        ')->first();


            $permitWiseCollection = $permitCollection->with_permit_number;

            $notpermitWiseCollection = $permitCollection->without_permit_number;

            // Permit number-wise data
            $permitData = [
                [
                    'status' => 'With Permit Number',
                    'count' => $permitWiseCollection,
                    'color' => '#17a2b8', // Info
                ],
                [
                    'status' => 'Without Permit Number',
                    'count' => $notpermitWiseCollection,
                    'color' => '#6c757d', // Secondary
                ]
            ];

           

            $data = [
                'statusWiseData' => $statusData,
                'approvalWiseData' => $approvalData,
                'permitWiseData' => $permitData,
                
            ];

            return $this->success('Property Report', $data, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
}
