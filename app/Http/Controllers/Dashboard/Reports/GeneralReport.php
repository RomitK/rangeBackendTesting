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
    Article,
    Guide,
    Agent,
    Career
};
use App\Jobs\{
    GeneralReportAndEmailData
};
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GeneralReport extends Controller
{
    public function ajaxData(Request $request)
    {

        $startDate = Carbon::parse($request->startDate);
        $endDate = Carbon::parse($request->endDate)->endOfDay();

        $interval = $startDate->diffInDays($endDate);


        $careerCounts = Career::getCountsByDate($startDate, $endDate);
        $careerStatusCounts = Career::getCountsByStatus($startDate, $endDate);

        $guideCounts = Guide::getCountsByDate($startDate, $endDate);
        $guideStatusCounts = Guide::getCountsByStatus($startDate, $endDate);

        $communityCounts = Community::getCountsByDate($startDate, $endDate);
        $communityStatusCounts = Community::getCountsByWebsiteStatus($startDate, $endDate);


        $developerCounts = Developer::getCountsByDate($startDate, $endDate);
        $developerStatusCounts = Developer::getCountsByWebsiteStatus($startDate, $endDate);


        $projectCounts = Project::getCountsByDate($startDate, $endDate);
        $projectStatusCounts = Project::getCountsByWebsiteStatus($startDate, $endDate);
        $projectPermitCounts = Project::getCountsByPermitNumber($startDate, $endDate);

        $propertyCounts = Property::getCountsByDate($startDate, $endDate);
        $propertyStatusCounts = Property::getCountsByWebsiteStatus($startDate, $endDate);

        $propertyPermitCounts = Property::getCountsByPermitNumber($startDate, $endDate);
        $propertyCateoryWiseCounts = Property::getCountsByCategory($startDate, $endDate);
        $propertyAgentWiseCount = Property::getCountsByAgent($startDate, $endDate);
        $propertyPermitCategoryWiseCount = Property::getCountsByPermitCategory($startDate, $endDate);


        $blogCounts = Article::getCountsByDate($startDate, $endDate);
        $blogStatusCounts = Article::getCountsByStatus($startDate, $endDate);
        $blogCategoryCounts = Article::getCountsByType($startDate, $endDate);

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
            if (!isset($guideCounts[$dateStr])) {
                $guideCounts[$dateStr] = 0;
            }
            if (!isset($careerCounts[$dateStr])) {
                $careerCounts[$dateStr] = 0;
            }
        }

        $projectPermitCounts = [
            [
                'status' => 'Without Permit Number+QR',
                'count' => [
                    'available' => $projectPermitCounts->without_permit_available,
                    'NA' => $projectPermitCounts->without_permit_NA,
                    'rejected' => $projectPermitCounts->without_permit_rejected,
                    'requested' => $projectPermitCounts->without_permit_requested
                ],

            ],
            [
                'status' => 'With Permit Number+QR',
                'count' => [
                    'available' => $projectPermitCounts->with_permit_available,
                    'NA' => $projectPermitCounts->with_permit_NA,
                    'rejected' => $projectPermitCounts->with_permit_rejected,
                    'requested' => $projectPermitCounts->with_permit_requested
                ],

            ]
        ];

        $propertyPermitCategoryCount = [
            [
                'status' => 'Without Permit Number+QR',
                'count' => [
                    'ready' => $propertyPermitCategoryWiseCount->without_permit_ready,
                    'offplan' => $propertyPermitCategoryWiseCount->without_permit_offplan,
                    'offplan_resale' => $propertyPermitCategoryWiseCount->without_permit_offplan_resale,
                    'rent' => $propertyPermitCategoryWiseCount->without_permit_rent,

                ],

            ],
            [
                'status' => 'With Permit Number+QR',
                'count' => [
                    'ready' => $propertyPermitCategoryWiseCount->with_permit_ready,
                    'offplan' => $propertyPermitCategoryWiseCount->with_permit_offplan,
                    'offplan_resale' => $propertyPermitCategoryWiseCount->with_permit_offplan_resale,
                    'rent' => $propertyPermitCategoryWiseCount->with_permit_rent,
                ],

            ]
        ];

        $propertyPermitCounts = [
            [
                'status' => 'Without Permit Number+QR',
                'count' => [
                    'available' => $propertyPermitCounts->without_permit_available,
                    'NA' => $propertyPermitCounts->without_permit_NA,
                    'rejected' => $propertyPermitCounts->without_permit_rejected,
                    'requested' => $propertyPermitCounts->without_permit_requested
                ],

            ],
            [
                'status' => 'With Permit Number+QR',
                'count' => [
                    'available' => $propertyPermitCounts->with_permit_available,
                    'NA' => $propertyPermitCounts->with_permit_NA,
                    'rejected' => $propertyPermitCounts->with_permit_rejected,
                    'requested' => $propertyPermitCounts->with_permit_requested
                ],

            ]
        ];

        $blogCategoryCounts = [
            [
                'status' => 'Awards',
                'count' => $blogCategoryCounts->firstWhere('article_type', 'Awards')->total ?? 0,
                'color' => '#17a2b8', // Info
            ],
            [
                'status' => 'News',
                'count' => $blogCategoryCounts->firstWhere('article_type', 'News')->total ?? 0,
                'color' => '#008000', // Secondary
            ],
            [
                'status' => 'Blogs',
                'count' => $blogCategoryCounts->firstWhere('article_type', 'Blogs')->total ?? 0,
                'color' => '#FFFF00', // Secondary
            ],
            [
                'status' => 'Celebrations',
                'count' => $blogCategoryCounts->firstWhere('article_type', 'Celebrations')->total ?? 0,
                'color' => '#6c757d', // Secondary
            ]
        ];
         //dd($propertyCateoryWiseCounts);
        $propertyCateoryCounts = [
            [
                'status' => 'Ready',
                'count' => [
                    'available' => $propertyCateoryWiseCounts->available_ready,
                    'NA' => $propertyCateoryWiseCounts->NA_ready,
                    'rejected' => $propertyCateoryWiseCounts->rejected_ready,
                    'requested' => $propertyCateoryWiseCounts->requested_ready
                ],

            ],
            [
                'status' => 'Offplan',
                'count' => [
                    'available' => $propertyCateoryWiseCounts->available_offplan,
                    'NA' => $propertyCateoryWiseCounts->NA_offplan,
                    'rejected' => $propertyCateoryWiseCounts->rejected_offplan,
                    'requested' => $propertyCateoryWiseCounts->requested_offplan
                ],

            ],
            [
                'status' => 'Offplan-Resale',
                'count' => [
                    'available' => $propertyCateoryWiseCounts->available_offplan_resale,
                    'NA' => $propertyCateoryWiseCounts->NA_offplan_resale,
                    'rejected' => $propertyCateoryWiseCounts->rejected_offplan_resale,
                    'requested' => $propertyCateoryWiseCounts->requested_offplan_resale
                ],

            ],
            [
                'status' => 'Rent',
                'count' => [
                    'available' => $propertyCateoryWiseCounts->available_rent,
                    'NA' => $propertyCateoryWiseCounts->NA_rent,
                    'rejected' => $propertyCateoryWiseCounts->rejected_rent,
                    'requested' => $propertyCateoryWiseCounts->requested_rent
                ],

            ],
        ];

        if (isset($request->download) && $request->download == 1) {
            $data = [
                'startDate' => $startDate->format('d m Y'),
                'endDate' => $endDate->format('d m Y'),
                'email' => Auth::user()->email,
                'userName' => Auth::user()->name,
                // 'communities' => Community::with(['user', 'projects', 'properties', 'approval'])->whereBetween('created_at', [$startDate, $endDate])->get(),
                // 'developers' => Developer::with(['user', 'projects', 'properties', 'approval'])->whereBetween('created_at', [$startDate, $endDate])->get(),
                // 'projects' => Project::with(['user', 'developer', 'mainCommunity', 'subProjects', 'properties', 'mPaymentPlans', 'approval'])->mainProject()->whereBetween('created_at', [$startDate, $endDate])->get(),
                // 'properties' => Property::with(['user', 'project', 'subProject', 'approval'])->whereBetween('created_at', [$startDate, $endDate])->get(),
                // 'blogs' => Article::with(['user'])->whereBetween('created_at', [$startDate, $endDate])->get(),
                // 'guides' => Guide::with(['user'])->whereBetween('created_at', [$startDate, $endDate])->get(),
                // 'careers' => Career::with(['user'])->whereBetween('created_at', [$startDate, $endDate])->get(),

                'getCountsByDate' =>  [
                    'interval' => $interval,
                    'communities' => $communityCounts,
                    'developers' => $developerCounts,
                    'projects' => $projectCounts,
                    'properties' => $propertyCounts,
                    'medias' => $blogCounts,
                    'guides' => $guideCounts,
                    'careers' => $careerCounts
                ],
                'getCountsByStatus' => [
                    'communities' => $communityStatusCounts,
                    'developers' => $developerStatusCounts,
                    'projects' => $projectStatusCounts,
                    'properties' => $propertyStatusCounts,
                    'medias' => $blogStatusCounts,
                    'guides' => $guideStatusCounts,
                    'careers' => $careerStatusCounts
                ],

                'projectPermitCounts' => $projectPermitCounts,
                'propertyPermitCounts' => $propertyPermitCounts,
                'propertyCateoryCounts' => $propertyCateoryCounts,
                'propertyAgentWiseCounts' => $propertyAgentWiseCount,
                'blogCategoryCounts' => $blogCategoryCounts
            ];
            GeneralReportAndEmailData::dispatch($data);

            return response()->json([
                'success' => true,
                'message' => 'Please Check Email, Report has been sent.',
            ]);
        }

        $data = [
            'getCountsByDate' =>  [
                'interval' => $interval,
                'communities' => $communityCounts,
                'developers' => $developerCounts,
                'projects' => $projectCounts,
                'properties' => $propertyCounts,
                'medias' => $blogCounts,
                'guides' => $guideCounts,
                'careers' => $careerCounts
            ],
            'getCountsByStatus' => [
                'communities' => $communityStatusCounts,
                'developers' => $developerStatusCounts,
                'projects' => $projectStatusCounts,
                'properties' => $propertyStatusCounts,
                'medias' => $blogStatusCounts,
                'guides' => $guideStatusCounts,
                'careers' => $careerStatusCounts
            ],

            'projectPermitCounts' => $projectPermitCounts,
            'propertyPermitCounts' => $propertyPermitCounts,
            'propertyCateoryCounts' => $propertyCateoryCounts,
            'propertyAgentWiseCounts' => $propertyAgentWiseCount,
            'blogCategoryCounts' => $blogCategoryCounts,
            'propertyPermitCategoryCount' => $propertyPermitCategoryCount

        ];


        return $this->success('General Report', $data, 200);
    }
    public function index(Request $request)
    {
        return view('dashboard.reports.general');
    }
}
