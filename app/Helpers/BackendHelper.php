<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

use App\Models\{
    Project,
    Community,
    Property,
    Developer,
    Article,
    Career,
    Guide,
    Agent,
    LogActivity
};

if (!function_exists('activeParentNavBar')) {
    function activeParentNavBar($parentNav, $className)
    {
        if ($parentNav == 'realEstate') {
            $childElements = [
                'dashboard.projects',
                'dashboard.properties',
                'dashboard.accommodations',
                'dashboard.amenities',
                'dashboard.highlights',
                'dashboard.specifications',
                'dashboard.features',
                'dashboard.offer-types',
                'dashboard.developers',
                'dashboard.developer',
                'dashboard.inventoryReport',
                'dashboard.completion-statuses',
                'dashboard.categories',
                'dashboard.communities',
                'dashboard.subCommunities',
                'dashboard.floorPlans',
            ];
        } elseif ($parentNav == 'leadManagement') {
            $childElements = [
                'dashboard.leads',
            ];
        } elseif ($parentNav == 'contentManagement') {
            $childElements = [
                'dashboard.articles',
                'dashboard.latestNews',
                'dashboard.video-gallery'
            ];
        } elseif ($parentNav == 'websiteSettings') {
            $childElements = [
                'dashboard.bulk-sms',
                'dashboard.recaptcha-site-key',
                'dashboard.social-info',
                'dashboard.basic-info'
            ];
        } elseif ($parentNav == 'reports') {
            $childElements = [
                'dashboard.reports.general-report',
                'dashboard.reports.developers',
                'dashboard.reports.communities',
                'dashboard.reports.properties',
                'dashboard.reports.projects',
                'dashboard.reports.inventory-report'
            ];
        } elseif ($parentNav == 'SEO') {
            $childElements = [
                'dashboard.page-tags',
            ];
        } elseif ($parentNav == 'userManagement') {
            $childElements = [
                'dashboard.users',
                'dashboard.roles',
            ];
        } elseif ($parentNav == 'pageContents') {

            $childElements = [
                'contents',
                'faqs',
                'dashboard.banners',
                'dashboard.counters',
                'dashboard.partners',
                'dashboard.dynamic-pages',
                'dashboard.pageContents.properties-page',
                'dashboard.pageContents.home-page',
                'dashboard.pageContents.career-page',
                'dashboard.pageContents.about-page',
                'dashboard.pageContents.rent-page',
                'dashboard.pageContents.resale-page',
                'dashboard.pageContents.offPlan-page',
                'dashboard.pageContents.developers-page',
                'dashboard.pageContents.communities-page',
                'dashboard.pageContents.faqs-page',
                'dashboard.pageContents.privacyPolicy-page',
                'dashboard.pageContents.termCondition',
                'dashboard.pageContents.dubaiGuide',
                'dashboard.pageContents.sellerGuide',
                'dashboard.pageContents.factFigure',
                'dashboard.pageContents.aboutDubai',
                'dashboard.pageContents.whyInvest',
            ];
        }

        foreach ($childElements as $child) {
            if (str_contains(Route::currentRouteName(), $child) == 1) {

                return $className;
            }
        }
    }
}
if (!function_exists('activeChildNavBar')) {
    function activeChildNavBar($routeName)
    {
        return (str_contains(Route::currentRouteName(), $routeName) == 1) ? 'active' : '';
    }
}
if (!function_exists('getFrontentRouteInfo')) {
    function getFrontentRouteInfo()
    {
        $frontendRoutes = [];
        $allRoutes = Route::getRoutes();
        foreach ($allRoutes as $key => $route) {
            if ($route->action['namespace'] == 'App\Http\Controllers\Frontend') {
                array_push($frontendRoutes, $route);
            }
        }
        return $frontendRoutes;
    }
}

function get_pagination()
{

    $pagination = [
        25  => 'Show up to 25 items',
        50  => '50 items',
        100 => '100 items',
        200 => '200 items',
        500 => '500 items'
    ];
    return $pagination;
}


if (!function_exists('getFrontentRouteInfo')) {
    function getFrontentRouteInfo()
    {
        $frontendRoutes = [];
        $allRoutes = Route::getRoutes();
        foreach ($allRoutes as $key => $route) {
            if ($route->action['namespace'] == 'App\Http\Controllers\Frontend') {
                array_push($frontendRoutes, $route);
            }
        }
        return $frontendRoutes;
    }
}

if (!function_exists('sendWebsiteStatReport')) {
    function sendWebsiteStatReport($recipients)
    {
        Log::info($recipients);
        try {
            $collection = Article::active()->approved();

            $medias = clone $collection;
            $news = clone $collection;
            $blogs = clone $collection;
            $awards = clone $collection;
            $celebrations = clone $collection;

            $propertiesCollection = Property::approved()->active();
            $propertiesCount = clone $propertiesCollection;
            $ready = clone $propertiesCollection;
            $offplan = clone $propertiesCollection;
            $offplanResale = clone $propertiesCollection;
            $rentProperties = clone $propertiesCollection;

            $data = [
                'allMedias' => $medias->count(),
                'types' => [
                    'News' => $news->news()->count(),
                    'Blogs' => $blogs->blogs()->count(),
                    'Awards' => $awards->awards()->count(),
                    'Celebrations' => $celebrations->celebrations()->count(),
                ],
                'teams' => Agent::active()->where('is_management', 0)->count(),
                'careers' => Career::active()->count(),
                'guides' => Guide::active()->approved()->count(),
                'communities' => Community::active()->approved()->count(),
                'developers' => Developer::active()->approved()->count(),
                'projects' => Project::approved()->active()->mainProject()->count(),
                'properties' => $propertiesCount->count(),
                'propertiesTypes' => [
                    'Ready' => $ready->buy()->where('completion_status_id', 286)->count(),
                    'Offplan' => $offplan->buy()->where('completion_status_id', 287)->count(),
                    'Offplan-Resale' => $offplanResale->buy()->where('completion_status_id', 291)->count(),
                    'Rent' => $rentProperties->rent()->count()
                ],
                'new_last_week' =>
                (array) DB::select("
                            SELECT 
                                DATE_SUB(CURDATE(), INTERVAL 7 DAY) AS start_date,
                                DATE_SUB(CURDATE(), INTERVAL 1 DAY) AS end_date
                        ")[0],
                'prev_last_week' =>
                (array) DB::select("
                        SELECT 
                        DATE_SUB(CURDATE(), INTERVAL 14 DAY) AS start_date,
                        DATE_SUB(CURDATE(), INTERVAL 8 DAY) AS end_date
                    ")[0],
                'careers_weekly' => (array)  DB::select("
                                            SELECT
                                                SUM(CASE WHEN created_at >= CURDATE() - INTERVAL 7 DAY THEN 1 ELSE 0 END) AS new_last_week_count,
                                                SUM(CASE WHEN created_at < CURDATE() - INTERVAL 7 DAY AND created_at >= CURDATE() - INTERVAL 14 DAY THEN 1 ELSE 0 END) AS prev_last_week_count
                                            FROM
                                            careers
                                            Where deleted_at is NULL
                                        ")[0],

                'guides_weekly' => (array) DB::select("
                                            SELECT
                                                SUM(CASE WHEN created_at >= CURDATE() - INTERVAL 7 DAY THEN 1 ELSE 0 END) AS new_last_week_count,
                                                SUM(CASE WHEN created_at < CURDATE() - INTERVAL 7 DAY AND created_at >= CURDATE() - INTERVAL 14 DAY THEN 1 ELSE 0 END) AS prev_last_week_count
                                            FROM
                                                guides
                                            Where deleted_at is NULL
                                        ")[0],
                'agents_weekly' => (array) DB::select("
                                        SELECT
                                            SUM(CASE WHEN created_at >= CURDATE() - INTERVAL 7 DAY THEN 1 ELSE 0 END) AS new_last_week_count,
                                            SUM(CASE WHEN created_at < CURDATE() - INTERVAL 7 DAY AND created_at >= CURDATE() - INTERVAL 14 DAY THEN 1 ELSE 0 END) AS prev_last_week_count
                                        FROM
                                            agents
                                        Where deleted_at is NULL
                                    ")[0],
                'articles_weekly' => (array) DB::select("
                                            SELECT
                                                SUM(CASE WHEN created_at >= CURDATE() - INTERVAL 7 DAY THEN 1 ELSE 0 END) AS new_last_week_count,
                                                SUM(CASE WHEN created_at < CURDATE() - INTERVAL 7 DAY AND created_at >= CURDATE() - INTERVAL 14 DAY THEN 1 ELSE 0 END) AS prev_last_week_count
                                            FROM
                                                articles
                                            Where deleted_at is NULL
                                        ")[0],

                'developers_weekly' => (array) DB::select("
                                            SELECT
                                                SUM(CASE WHEN created_at >= CURDATE() - INTERVAL 7 DAY THEN 1 ELSE 0 END) AS new_last_week_count,
                                                SUM(CASE WHEN created_at < CURDATE() - INTERVAL 7 DAY AND created_at >= CURDATE() - INTERVAL 14 DAY THEN 1 ELSE 0 END) AS prev_last_week_count
                                            FROM
                                                developers
                                            Where deleted_at is NULL
                                        ")[0],

                'communities_weekly' => (array) DB::select("
                                        SELECT
                                            SUM(CASE WHEN created_at >= CURDATE() - INTERVAL 7 DAY THEN 1 ELSE 0 END) AS new_last_week_count,
                                            SUM(CASE WHEN created_at < CURDATE() - INTERVAL 7 DAY AND created_at >= CURDATE() - INTERVAL 14 DAY THEN 1 ELSE 0 END) AS prev_last_week_count
                                        FROM
                                            communities
                                        Where deleted_at is NULL
                                    ")[0],

                'projects_weekly' => (array) DB::select("
                                    SELECT
                                        SUM(CASE WHEN created_at >= CURDATE() - INTERVAL 7 DAY THEN 1 ELSE 0 END) AS new_last_week_count,
                                        SUM(CASE WHEN created_at < CURDATE() - INTERVAL 7 DAY AND created_at >= CURDATE() - INTERVAL 14 DAY THEN 1 ELSE 0 END) AS prev_last_week_count
                                    FROM
                                        projects
                                    Where deleted_at is NULL AND is_parent_project = 1
                                ")[0],

                'properties_weekly' => (array)  DB::select("
                                            SELECT
                                                SUM(CASE WHEN created_at >= CURDATE() - INTERVAL 7 DAY THEN 1 ELSE 0 END) AS new_last_week_count,
                                                SUM(CASE WHEN created_at < CURDATE() - INTERVAL 7 DAY AND created_at >= CURDATE() - INTERVAL 14 DAY THEN 1 ELSE 0 END) AS prev_last_week_count
                                            FROM
                                                properties
                                            Where deleted_at is NULL
                                        ")[0]
            ];

            Log::info($data);

            foreach ($recipients as $recipient) {
                $name = $recipient['name'];
                $email = $recipient['email'];

                $data['userName'] = $name; // Change userName for each recipient
                $data['email'] = $email;

                Mail::send('mails.websiteStatReport', ['data' => $data], function ($message) use ($email, $name) {
                    $message->to($email, $name)->subject('Website Stat Report');
                });
            }
        } catch (\Exception $error) {
            Log::info("WeeklyWebsiteStateReportJob-error" . $error->getMessage());
        }
    }
}
if (!function_exists('countPropertiesForDeveloper')) {
    function countPropertiesForDeveloper($developerId)
    {
        // Load the developer with projects and count properties using eager loading with count
        $developer = Developer::with(['projects' => function ($query) {
            $query->withCount('properties');
        }])->findOrFail($developerId);

        // Calculate the total properties count
        $totalPropertiesCount = $developer->projects->sum('properties_count');

        return $totalPropertiesCount;
    }
}
if (!function_exists('countPropertiesForCommunity')) {
    function countPropertiesForCommunity($communityId)
    {
        // Load the developer with projects and count properties using eager loading with count
        $community = Community::with(['projects' => function ($query) {
            $query->withCount('properties');
        }])->findOrFail($communityId);

        // Calculate the total properties count
        $totalPropertiesCount = $community->projects->sum('properties_count');

        return $totalPropertiesCount;
    }
}

if (!function_exists('logActivity')) {
    function logActivity($description, $subject_id = 0, $type, $model)
    {

        $obj                = new LogActivity;
        $obj->log_name      = 'default';
        $obj->description   = $description;
        $obj->subject_id    = $subject_id;
        $obj->subject_type  = $type;
        $obj->causer_id     = Auth::check() ? Auth::User()->id : 0;
        $obj->causer_type   = $type;
        $obj->properties    = $model;

        $obj->save();
    }
}
if (!function_exists('getActivity')) {
    function getActivity($activity_id)
    {

        $logActivity = LogActivity::find($activity_id);
        dd($logActivity);
        $user = $logActivity->user ? $logActivity->user->full_name : 'Auto Assigned';

        if ($logActivity->subject_type == 'App\LeadAgent') {
            return $user . ', #' . $logActivity->subject_id . ' ' . $logActivity->description;
        } else if ($logActivity->subject_type == 'App\Lead') {
            return $user . ', ' . $logActivity->description . ', #' . $logActivity->subject_id;
        } else {
            return $user . ', ' . $logActivity->description;
        }
    }
}

if (!function_exists('getUpdatedPropertiesForProperty')) {
    function getUpdatedPropertiesForProperty($newPropertyOriginalAttributes, $originalAttributes)
    {
        Log::info('newPropertyOriginalAttributes', $newPropertyOriginalAttributes);
        Log::info('originalAttributes', $originalAttributes);

        // Convert specific attributes to integer arrays if they exist
        $keysToConvert = ['developerIds', 'amenityIds', 'highlightIds'];

        foreach ($keysToConvert as $key) {
            if (isset($newPropertyOriginalAttributes[$key]) && is_array($newPropertyOriginalAttributes[$key])) {
                $newPropertyOriginalAttributes[$key] = array_map('intval', $newPropertyOriginalAttributes[$key]);
            }
            if (isset($originalAttributes[$key]) && is_array($originalAttributes[$key])) {
                $originalAttributes[$key] = array_map('intval', $originalAttributes[$key]);
            }
        }

        // Determine the updated attributes
        $updatedAttributes = [];

        foreach ($newPropertyOriginalAttributes as $key => $value) {
            if (!in_array($key, ['created_at', 'updated_at'])) {
                // Ensure the original attribute exists and compare based on type
                if (array_key_exists($key, $originalAttributes)) {
                    if (is_string($value) && $originalAttributes[$key] != $value) {
                        $updatedAttributes[$key] = $value;
                    } elseif (is_array($value) && serialize($originalAttributes[$key]) !== serialize($value)) {
                        $updatedAttributes[$key] = $value;
                    }
                } else {
                    // Handle case where $originalAttributes[$key] does not exist
                    $updatedAttributes[$key] = $value;
                }
            }
        }

        // Construct the updated attributes strings
        $updatedAttributesString = implode(', ', array_map(
            fn ($value, $key) => "$key: " . (is_array($value) ? json_encode($value) : $value),
            $updatedAttributes,
            array_keys($updatedAttributes)
        ));

        $updatedCoumnAttributesString = implode(', ', array_keys($updatedAttributes));

        // Encode the properties to JSON
        $properties = json_encode([
            'old' => $originalAttributes,
            'new' => $newPropertyOriginalAttributes,
            'updateAttribute' => $updatedCoumnAttributesString,
            'attribute' => $updatedAttributesString
        ]);
        Log::info('properties' . $properties);
        return $properties;
    }
}

if (!function_exists('generatePropertyUniqueCode')) {
    function generatePropertyUniqueCode($prefix)
    {

        do {
            $code = $prefix . random_int(1000000, 9999999);
        } while (Property::where("reference_number", $code)->first());
        return $code;
    }
}
if (!function_exists('slugToOriginal')) {
    function slugToOriginal($slug)
    {
        // Convert the slug back to the original string
        return ucwords(str_replace('-', ' ', $slug));
    }
}
