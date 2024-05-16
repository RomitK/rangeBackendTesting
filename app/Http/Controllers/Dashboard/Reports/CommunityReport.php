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
    Property
};
use Carbon\Carbon;

class CommunityReport extends Controller
{
    public function ajaxData(Request $request)
    {
        $startDate = Carbon::parse($request->startDate);
        $endDate = Carbon::parse($request->endDate);
        $interval = $startDate->diffInDays($endDate);
        $communities = Community::get();


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
        }
        $data = [
            'interval' => $interval,
            'communityCounts' => $communityCounts,
            'developerCounts' => $developerCounts,
            'projectCounts' => $projectCounts,
            'propertyCounts' => $propertyCounts
        ];

        return response()->json($data);
    }
    public function general(Request $request)
    {
        return view('dashboard.reports.general');
    }
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
        // if (isset($request->date_range)) {
        //     $dateString = $request->date_range;
        //     $dateParts = explode(" - ", $dateString);
        //     $startDate = $dateParts[0];
        //     $endDate = $dateParts[1];

        //     $collection->whereBetween('created_at', [$startDate, $endDate]);
        // } else {
        //     $collection->whereDate('created_at', Carbon::today());
        // }
        if (isset($request->display_on_home)) {
            $collection->where('display_on_home', $request->display_on_home);
        }
        if (isset($request->developer_ids)) {
            $developer_ids =  $request->developer_ids;

            $collection->whereHas('developers', function ($query) use ($developer_ids) {
                $query->whereIn('developers.id', $developer_ids);
            });
        }
        if (isset($request->is_approved)) {
            $collection->where('is_approved', $request->is_approved);
        }

        if (isset($request->keyword)) {
            $keyword = $request->keyword;
            $collection->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%");
            });
        }

        if (isset($request->orderby)) {
            $orderBy = $request->input('orderby', 'created_at'); // default_column is the default field to sort by
            $direction = $request->input('direction', 'asc'); // Default sorting direction
            $communities = $collection->orderByRaw('ISNULL(communityOrder)')->orderBy($orderBy, $direction)->paginate($current_page);
        } else {
            $communities = $collection->latest()->paginate($current_page);
        }

        return view('dashboard.reports.communities.index', compact(
            'communities',
            'sr_no_start',
            'current_page'
        ));
    }
}
