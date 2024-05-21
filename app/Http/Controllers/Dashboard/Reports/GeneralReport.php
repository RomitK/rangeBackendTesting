<?php

namespace App\Http\Controllers\Dashboard\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
    Property,
    Article
};
use Carbon\Carbon;

class GeneralReport extends Controller
{
    public function ajaxData(Request $request)
    {
        $startDate = Carbon::parse($request->startDate);
        $endDate = Carbon::parse($request->endDate);
        $interval = $startDate->diffInDays($endDate);


        $communityCounts = Community::whereBetween('created_at', [$startDate, $endDate])
            ->whereNull('deleted_at') // Include condition for deleted_at is null
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $developerCounts = Developer::whereBetween('created_at', [$startDate, $endDate])
            ->whereNull('deleted_at') // Include condition for deleted_at is null
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $projectCounts = Project::whereBetween('created_at', [$startDate, $endDate])
            ->whereNull('deleted_at') // Include condition for deleted_at is null
            ->where('is_parent_project', true)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $propertyCounts = Property::whereBetween('created_at', [$startDate, $endDate])
            ->whereNull('deleted_at') // Include condition for deleted_at is null
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $blogCounts = Article::whereBetween('created_at', [$startDate, $endDate])
            ->whereNull('deleted_at') // Include condition for deleted_at is null
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();




        // Calculate interval in days
        $interval = $startDate->diffInDays($endDate) + 1;

        // Fill in missing dates with zero counts
        $startDateStr = $startDate->toDateString();
        $endDateStr = $endDate->toDateString();
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {

            $dateStr = $date->toDateString();

            if (!isset($communityCounts[$dateStr])) {
                $communityCounts[$dateStr] = 0;
            }

            if (!isset($developerCounts[$dateStr])) {
                $developerCounts[$dateStr] = 0;
            }
            if (!isset($projectCounts[$dateStr])) {
                $projectCounts[$dateStr] = 0;
            }
            if (!isset($propertyCounts[$dateStr])) {
                $propertyCounts[$dateStr] = 0;
            }
            if (!isset($blogCounts[$dateStr])) {
                $blogCounts[$dateStr] = 0;
            }
        }
       
        $data = [
            'interval' => $interval,
            'communityCounts' => $communityCounts,
            'developerCounts' => $developerCounts,
            'projectCounts' => $projectCounts,
            'propertyCounts' => $propertyCounts,
            'mediaCounts' => $blogCounts,
        ];

        return response()->json($data);
    }
    public function index(Request $request)
    {
        return view('dashboard.reports.general');
    }
}
