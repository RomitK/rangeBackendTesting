<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/upload-test', function () {
    return view('upload-test');
});


Route::post('/upload-test', function () {
    $maxUploadSize = ini_get('upload_max_filesize');
    $maxPostSize = ini_get('post_max_size');

    // Logic to handle file upload
    if (!empty($_FILES['uploaded_file'])) {
        $path = "uploads/";
        $path = $path . basename($_FILES['uploaded_file']['name']);
        $move = move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path);

        if ($move) {
            echo "The file " . basename($_FILES['uploaded_file']['name']) . " has been uploaded";
        } else {
            echo "Failed to upload the file";
        }
    } else {
        echo "No file uploaded";
    }
});



Route::get('/file_info', function () {
    $maxUploadSize = ini_get('upload_max_filesize');
    $maxPostSize = ini_get('post_max_size');
    echo "maxUploadSize-" . $maxUploadSize . "maxPostSize-" . $maxPostSize;
});

Route::get('/phpinfo', function () {
    phpinfo();
});

Route::get('/delete/logs', function () {
    exec('rm ' . storage_path('logs/*.log'));
    return "clear successfully";
});

Route::get('/read/logs', function () {
    try {
        $logFile = file(storage_path() . '/logs/laravel.log');
        if ($logFile) {
            $logCollection = array();
            foreach ($logFile as $line_num => $line) {
                $logCollection[] = array('line' => $line_num, 'content' => htmlspecialchars($line));
            }
            return response()->json($logCollection);
        }
    } catch (\Exception $e) {
        return "no file foud";
    }
});

// clear chache route
Route::get('/clear-cache', function () {
    $exitCode    = Artisan::call('cache:clear');
    // $config      = Artisan::call('config:cache');
    // $view        = Artisan::call('view:clear');
    // $route        = Artisan::call('route:clear');
    // $optimize        = Artisan::call('optimize:clear');
    return "Cache is cleared";
});

/*******************
 * FRONTEND ROUTES  *
 *******************/
Route::get('/contactInsert', 'App\Http\Controllers\CronController@contactInsert');

Route::get('/propertiesPermitNumber', 'App\Http\Controllers\CronController@propertiesPermitNumber');
Route::get('/getRentListings', 'App\Http\Controllers\CronController@getRentListings');
Route::get('/getSaleListings', 'App\Http\Controllers\CronController@getSaleListings');


Route::get('cronJobmakeInctiveProperties',  'App\Http\Controllers\CronController@cronJobmakeInctiveProperties');

Route::get('cronjob/deleteNAProperties', 'App\Http\Controllers\CronController@deleteNAProperties');


Route::get('projectQR', 'App\Http\Controllers\CronController@projectQR');

Route::get('inactiveProperties', 'App\Http\Controllers\CronController@inactiveProperties');

Route::get('activeProperties', 'App\Http\Controllers\CronController@activeProperties');
Route::get('NAProperties', 'App\Http\Controllers\CronController@NAProperties');

Route::get('cronjob/subProjects', 'App\Http\Controllers\CronController@subProjects');

Route::get('makeRequest', 'App\Http\Controllers\CronController@makeRequest');
Route::get('cronjob/propertyBanner', 'App\Http\Controllers\CronController@propertyBanner');
Route::get('cronjob/propertyUpdate', 'App\Http\Controllers\CronController@propertyUpdate');
Route::get('cronjob/projectBrochure', 'App\Http\Controllers\CronController@projectBrochure');
Route::get('cronjob/propertyWaterMark', 'App\Http\Controllers\CronController@propertyWaterMark');
Route::get('cronjob/property/addxml', 'App\Http\Controllers\CronController@propertyWaterMark');



Route::namespace('App\Http\Controllers\Frontend')->group(function () {


    Route::get('/converter', 'HomeController@converter');
    
    Route::get('/export-api-response', 'HomeController@DLDTransaction');

    Route::any('/', 'HomeController@showLoginPage')->name('home');
    Route::any('communities', 'HomeController@communities')->name('communities');
    Route::any('off-plan', 'HomeController@offPlan')->name('off-plan');
    Route::any('properties', 'HomeController@properties')->name('properties');
    Route::any('properties-demo', 'HomeController@propertiesDemo')->name('properties-demo');
    Route::any('properties-demos', 'HomeController@propertiesDemoS')->name('properties-demos');

    Route::any('buy', 'HomeController@buy')->name('buy');
    Route::any('ready', 'HomeController@buy')->name('ready');
    Route::any('rent', 'HomeController@rent')->name('rent');
    Route::any('luxury-properties', 'HomeController@luxuryProperties')->name('luxury-properties');
    Route::any('singleProject', 'HomeController@singleProject')->name('singleProject');
    Route::any('singleCommunity', 'HomeController@singleCommunity')->name('singleCommunity');
    Route::any('singleDeveloper', 'HomeController@singleDeveloper')->name('singleDeveloper');
    Route::any('about-us', 'HomeController@aboutUs')->name('about-us');
    Route::any('contact-us', 'HomeController@contact')->name('contact-us');
    Route::any('privacy-policy', 'HomeController@privacyPolicy')->name('privacy-policy');
    Route::any('terms-conditions', 'HomeController@termsConditions')->name('terms-conditions');
    Route::any('thank-you', 'HomeController@thankYou')->name('thank-you');

    Route::any('developer/{slug}', 'HomeController@singleDeveloperPage')->name('developer.view');
    Route::any('community/{slug}', 'HomeController@singleCommunityPage')->name('community.view');
    Route::any('property/{slug}', 'HomeController@singlePropertyPage')->name('property.view');

    Route::get('project/{slug}/brochure', 'HomeController@singleProjectBrochure');
    Route::get('project/{slug}/saleOffer', 'HomeController@singleProjectSaleOffer');

    Route::get('property/{slug}/brochure', 'HomeController@singlePropertyBrochure');
    Route::get('property/{slug}/saleOffer', 'HomeController@singlePropertySaleOffer');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('auth')->name('home');

Route::group(['namespace' => 'App\Http\Controllers\Dashboard', 'prefix' => 'dashboard', 'middleware' => 'auth'], function () {

    Route::get('inventory-report', 'Reports\InventoryReport@index')->name('dashboard.reports.inventory-report');

    Route::get('inventoryReport', 'Reports\InventoryReport@index')->name('dashboard.inventoryReport');
    Route::get('projects/{project}/inventoryList', 'ProjectController@inventoryList')->name('dashboard.inventoryReport.list');
    Route::get('projects/{project}/inventoryCreate', 'ProjectController@inventoryCreate')->name('dashboard.inventoryReport.create');
    Route::post('projects/{project}/inventoryStore', 'ProjectController@inventoryStore')->name('dashboard.inventoryReport.store');


    Route::put('projects/{project}/inventoryUpdate', 'ProjectController@inventoryUpdate')->name('dashboard.projects.inventoryUpdate');


    Route::get('general-report', 'Reports\GeneralReport@index')->name('dashboard.reports.general-report');
    Route::get('ajaxData', 'Reports\GeneralReport@ajaxData')->name('reports.ajax-data');

    Route::get('communities-report', 'Reports\CommunityReport@index')->name('dashboard.reports.communities');
    Route::get('ajaxCommunityReport', 'Reports\CommunityReport@ajaxCommunityReportData');

    Route::get('developers-report', 'Reports\DeveloperReport@index')->name('dashboard.reports.developers');
    Route::get('ajaxDeveloperReport', 'Reports\DeveloperReport@ajaxDeveloperReport');

    Route::get('projects-report', 'Reports\ProjectReport@index')->name('dashboard.reports.projects');
    Route::get('ajaxProjectReport', 'Reports\ProjectReport@ajaxProjectReport');


    Route::get('properties-report', 'Reports\PropertyReport@index')->name('dashboard.reports.properties');
    Route::get('ajaxPropertyReport', 'Reports\PropertyReport@ajaxPropertyReport');


    Route::resource('roles', RoleController::class, ['as' => 'dashboard']);
    Route::resource('tags', TagController::class, ['as' => 'dashboard']);
    Route::resource('offer-types', OfferTypeController::class, ['as' => 'dashboard']);
    Route::resource('developers', DeveloperController::class, ['as' => 'dashboard']);

    Route::get('developers/{developer}/media/{media}', 'DeveloperController@mediaDestroy')->name('dashboard.developers.media.delete');
    Route::get('developers/{developer}/medias', 'DeveloperController@mediasDestroy')->name('dashboard.developers.medias.delete');

    Route::resource('defaultStats', DefaultStatController::class, ['as' => 'dashboard']);

    Route::get('developers/{developer}/meta', 'DeveloperController@meta')->name('dashboard.developers.meta');
    Route::post('developers/{developer}/meta', 'DeveloperController@updateMeta')->name('dashboard.developers.meta.store');

    Route::get('developers/{developer}/logs', 'DeveloperController@logs')->name('dashboard.developers.logs');

    Route::get('developer/{developer}/details', 'DeveloperController@details')->name('dashboard.developer.details');
    Route::get('developer/{developer}/details/create', 'DeveloperController@createDetail')->name('dashboard.developer.details.create');
    Route::post('developer/{developer}/details', 'DeveloperController@storeDetail')->name('dashboard.developer.details.store');
    Route::get('developer/{developer}/details/{detail}/edit', 'DeveloperController@editDetail')->name('dashboard.developer.details.edit');
    Route::put('developer/{developer}/details/{detail}', 'DeveloperController@updateDetail')->name('dashboard.developer.details.update');
    Route::delete('developer/{developer}/details/{detail}', 'DeveloperController@destroyDetail')->name('dashboard.developer.details.destroy');
    Route::get('developer/mainImage', 'DeveloperController@mainImage');


    Route::resource('agents', AgentController::class, ['as' => 'dashboard']);
    Route::resource('completion-statuses', CompletionStatusController::class, ['as' => 'dashboard']);
    Route::resource('amenities', AmenityController::class, ['as' => 'dashboard']);
    Route::resource('highlights', HighlightController::class, ['as' => 'dashboard']);
    Route::resource('neighbours', NeighbourController::class, ['as' => 'dashboard']);
    Route::resource('subCommunities', SubCommunityController::class, ['as' => 'dashboard']);
    Route::resource('features', FeatureController::class, ['as' => 'dashboard']);
    Route::resource('accommodations', AccommodationController::class, ['as' => 'dashboard']);

    // Route::get('communities/{community}/edit',  'CommunityController@edit');
    Route::resource('communities', CommunityController::class, ['as' => 'dashboard']);

    Route::get('communities/{community}/logs', 'CommunityController@logs')->name('dashboard.communities.logs');

    Route::get('communities/{community}/meta', 'CommunityController@meta')->name('dashboard.communities.meta');
    Route::post('communities/{community}/meta', 'CommunityController@updateMeta')->name('dashboard.community.meta.store');
    Route::get('community/mainImage', 'CommunityController@mainImage');
    Route::get('communities/{community}/media/{media}', 'CommunityController@mediaDestroy')->name('dashboard.communities.media.delete');
    Route::get('communities/{community}/medias', 'CommunityController@mediasDestroy')->name('dashboard.communities.medias.delete');

    Route::post('developer/communities', 'CommunityController@developerCommunities')->name('dashboard.developers.communities');
    Route::post('community/subcommunities', 'CommunityController@subCommunities')->name('dashboard.community.subcommunities');
    Route::post('community/developers', 'CommunityController@developers')->name('dashboard.community.developers');
    Route::post('community/projects', 'CommunityController@projects')->name('dashboard.community.projects');

    Route::get('communities/{community}/stats', 'CommunityStatController@index')->name('dashboard.communities.stats');
    Route::get('communities/{community}/stats/create', 'CommunityStatController@create')->name('dashboard.communities.stats.create');
    Route::post('communities/{community}/stats', 'CommunityStatController@store')->name('dashboard.communities.stats.store');
    Route::get('communities/{community}/stats/{stat}/edit', 'CommunityStatController@edit')->name('dashboard.communities.stats.edit');
    Route::put('communities/{community}/stats/{stat}', 'CommunityStatController@update')->name('dashboard.communities.stats.update');
    Route::delete('communities/{community}/stats/{stat}', 'CommunityStatController@destroy')->name('dashboard.communities.stats.destroy');

    Route::get('communities/{community}/stats/{stat}/statData', 'CommunityStatController@values')->name('dashboard.communities.stats.statData');
    Route::get('communities/{community}/stats/{stat}/statData/create', 'CommunityStatController@createValue')->name('dashboard.communities.stats.statData.create');
    Route::post('communities/{community}/stats/{stat}/statData', 'CommunityStatController@storeValue')->name('dashboard.communities.stats.statData.store');
    Route::get('communities/{community}/stats/{stat}/statData/{statData}/edit', 'CommunityStatController@editValue')->name('dashboard.communities.stats.statData.edit');
    Route::put('communities/{community}/stats/{stat}/statData/{statData}', 'CommunityStatController@updateValue')->name('dashboard.communities.stats.statData.update');
    Route::delete('communities/{community}/stats/{stat}/statData/{statData}', 'CommunityStatController@destroyValue')->name('dashboard.communities.stats.statData.destroy');

    Route::resource('floorPlans', FloorPlanController::class, ['as' => 'dashboard']);
    Route::get('floorPlans/{floorPlan}/subFloor/{subFloorPlan}', 'FloorPlanController@destroySubFloor')->name('dashboard.floorPlans.subFloor.destroy');
    Route::resource('categories', CategoryController::class, ['as' => 'dashboard']);
    Route::resource('awards', AwardController::class, ['as' => 'dashboard']);
    Route::get('awards/{award}/media/{media}', 'AwardController@mediaDestroy')->name('dashboard.awards.media.delete');
    Route::get('awards/{award}/medias', 'AwardController@mediasDestroy')->name('dashboard.awards.medias.delete');

    Route::get('projects/{project}/inventoryDownload', 'ProjectController@inventoryDownload')->name('dashboard.projects.inventoryDownload');

    Route::get('properties/{property}/updateBrochure', 'PropertyController@updateBrochure')->name('dashboard.properties.updateBrochure');

    Route::resource('properties', PropertyController::class, ['as' => 'dashboard']);
    Route::get('properties/{property}/logs', 'PropertyController@logs')->name('dashboard.properties.logs');
    Route::get('properties/{property}/meta', 'PropertyController@meta')->name('dashboard.properties.meta');
    Route::post('properties/{property}/meta', 'PropertyController@updateMeta')->name('dashboard.property.meta.store');
    Route::get('properties/{property}/duplicate', 'PropertyController@duplicateProperty')->name('dashboard.properties.duplicate');
    Route::get('properties/{property}/media/{media}', 'PropertyController@mediaDestroy')->name('dashboard.properties.media.delete');
    Route::get('properties/{property}/medias', 'PropertyController@mediasDestroy')->name('dashboard.properties.medias.delete');

    Route::resource('projects', ProjectController::class, ['as' => 'dashboard']);
    Route::get('projects/{project}/logs', 'ProjectController@logs')->name('dashboard.projects.logs');
    Route::get('projects/{project}/inventory', 'ProjectController@inventory')->name('dashboard.projects.inventory');
    Route::post('projects/update-property/{property}', 'ProjectController@inventoryUpdate1')->name('dashboard.projects.inventoryUpdateField');

    Route::get('projects/{project}/meta', 'ProjectController@meta')->name('dashboard.projects.meta');
    Route::post('projects/{project}/meta', 'ProjectController@updateMeta')->name('dashboard.project.meta.store');
    Route::get('project/mainImage', 'ProjectController@mainImage');
    Route::get('projects/{project}/media/{media}', 'ProjectController@mediaDestroy')->name('dashboard.projects.media.delete');
    // Route::get('projects/{project}/interiorMediasDestroy', 'ProjectController@interiorMediasDestroy')->name('dashboard.projects.interiorMediasDestroy');
    // Route::get('projects/{project}/exteriorMediasDestroy', 'ProjectController@exteriorMediasDestroy')->name('dashboard.projects.exteriorMediasDestroy');


    Route::get('projects/{project}/updateBrochure', 'ProjectController@updateBrochure')->name('dashboard.projects.updateBrochure');
    Route::get('projects/{project}/viewBrochure', 'ProjectController@viewBrochure')->name('dashboard.projects.viewBrochure');

    Route::any('singleProjectDetail', 'ProjectController@singleProjectDetail')->name('dashboard.project.ajax');
    Route::any('projects/subProjects', 'ProjectController@subProjects')->name('dashboard.project.subprojects');
    Route::get('projects/{project}/paymentPlans', 'ProjectController@payments')->name('dashboard.projects.paymentPlans');
    Route::get('projects/{project}/paymentPlans/create', 'ProjectController@createPayment')->name('dashboard.projects.paymentPlans.create');
    Route::post('projects/{project}/paymentPlans', 'ProjectController@storePayment')->name('dashboard.projects.paymentPlans.store');
    Route::get('projects/{project}/paymentPlans/{payment}/edit', 'ProjectController@editPayment')->name('dashboard.projects.paymentPlans.edit');
    Route::put('projects/{project}/paymentPlans/{payment}', 'ProjectController@updatePayment')->name('dashboard.projects.paymentPlans.update');
    Route::delete('projects/{project}/paymentPlans/{payment}', 'ProjectController@destroyPayment')->name('dashboard.projects.paymentPlans.destroy');

    Route::resource('partners', PartnerController::class, ['as' => 'dashboard']);


    Route::post('home/contents', 'PageContentController@homeContentStore')->name('dashboard.homePage.contents.store');
    Route::post('about/contents', 'PageContentController@aboutContentStore')->name('dashboard.aboutPage.contents.store');
    Route::post('ceo/contents', 'PageContentController@ceoContentStore')->name('dashboard.ceoPage.contents.store');

    Route::post('about/gallery', 'PageContentController@aboutGalleryStore')->name('dashboard.about.gallery.store');
    Route::get('about/gallery/{gallery}', 'PageContentController@aboutGalleryDestroy')->name('dashboard.about.gallery.destroy');


    Route::get('{page}/contents/create', 'PageContentController@create')->name('dashboard.contents.create');
    Route::post('contents', 'PageContentController@store')->name('dashboard.contents.store');
    Route::get('{page}/contents/{content}/edit', 'PageContentController@edit')->name('dashboard.contents.edit');
    Route::put('{page}/contents/{content}', 'PageContentController@update')->name('dashboard.contents.update');
    Route::delete('{page}/contents/{content}', 'PageContentController@destroy')->name('dashboard.contents.destroy');


    Route::get('{page}/faqs/create', 'FaqController@create')->name('dashboard.faqs.create');
    Route::post('faqs', 'FaqController@store')->name('dashboard.faqs.store');
    Route::get('{page}/faqs/{faq}/edit', 'FaqController@edit')->name('dashboard.faqs.edit');
    Route::put('{page}/faqs/{faq}', 'FaqController@update')->name('dashboard.faqs.update');
    Route::delete('{page}/faqs/{faq}', 'FaqController@destroy')->name('dashboard.faqs.destroy');



    Route::get('{page}/guides/create', 'GuideController@create')->name('dashboard.guides.create');
    Route::post('guides', 'GuideController@store')->name('dashboard.guides.store');
    Route::get('{page}/guides/{guide}/edit', 'GuideController@edit')->name('dashboard.guides.edit');
    Route::put('{page}/guides/{guide}', 'GuideController@update')->name('dashboard.guides.update');
    Route::delete('{page}/guides/{guide}', 'GuideController@destroy')->name('dashboard.guides.destroy');



    Route::resource('dynamicPages', DynamicPageController::class, ['as' => 'dashboard']);
    Route::get('pageContents/home-page', 'PageContentController@homePage')->name('dashboard.pageContents.home-page');
    Route::get('pageContents/career-page', 'PageContentController@careerPage')->name('dashboard.pageContents.career-page');
    Route::get('pageContents/dubaiGuide-page', 'PageContentController@dubaiGuidePage')->name('dashboard.pageContents.dubaiGuide-page');
    Route::post('pageContents/dubaiGuideStore', 'PageContentController@dubaiGuideStore')->name('dashboard.pageContents.dubaiGuideStore');
    Route::post('pageContents/sellerGuideStore', 'PageContentController@sellerGuideStore')->name('dashboard.pageContents.sellerGuide');
    Route::post('pageContents/homeStore', 'PageContentController@homeStore')->name('dashboard.pageContents.homeStore');

    Route::get('pageContents/about-page', 'PageContentController@aboutPage')->name('dashboard.pageContents.about-page');
    Route::get('pageContents/properties-page', 'PageContentController@propertiesPage')->name('dashboard.pageContents.properties-page');
    Route::get('pageContents/rent-page', 'PageContentController@rentPage')->name('dashboard.pageContents.rent-page');
    Route::get('pageContents/resale-page', 'PageContentController@resalePage')->name('dashboard.pageContents.resale-page');
    Route::get('pageContents/offPlan-page', 'PageContentController@offPlanPage')->name('dashboard.pageContents.offPlan-page');
    Route::get('pageContents/developers-page', 'PageContentController@developersPage')->name('dashboard.pageContents.developers-page');
    Route::get('pageContents/communities-page', 'PageContentController@communitiesPage')->name('dashboard.pageContents.communities-page');
    Route::get('pageContents/privacyPolicy-page', 'PageContentController@privacyPolicyPage')->name('dashboard.pageContents.privacyPolicy-page');
    Route::get('pageContents/termCondition-page', 'PageContentController@termConditionPage')->name('dashboard.pageContents.termCondition-page');
    Route::get('pageContents/buyerGuide-page', 'PageContentController@buyerGuidePage')->name('dashboard.pageContents.buyerGuide-page');
    Route::get('pageContents/dubaiGuide-page', 'PageContentController@dubaiGuidePage')->name('dashboard.pageContents.dubaiGuide-page');
    Route::get('pageContents/sellerGuide-page', 'PageContentController@sellerGuidePage')->name('dashboard.pageContents.sellerGuide-page');
    Route::get('pageContents/whyInvest-page', 'PageContentController@whyInvestPage')->name('dashboard.pageContents.whyInvest-page');
    Route::get('pageContents/aboutDubai-page', 'PageContentController@aboutDubaiPage')->name('dashboard.pageContents.aboutDubai-page');
    Route::get('pageContents/factFigure-page', 'PageContentController@factFigurePage')->name('dashboard.pageContents.factFigure-page');
    Route::get('pageContents/faqs-page', 'PageContentController@faqsPage')->name('dashboard.pageContents.faqs-page');
    Route::get('pageContents/relocatingToDubai-page', 'PageContentController@relocatingToDubaiPage')->name('dashboard.pageContents.relocatingToDubai-page');

    Route::get('projects/{project}/stats', 'ProjectStatController@index')->name('dashboard.projects.stats');
    Route::get('projects/{project}/stats/create', 'ProjectStatController@create')->name('dashboard.projects.stats.create');
    Route::post('projects/{project}/stats', 'ProjectStatController@store')->name('dashboard.projects.stats.store');
    Route::get('projects/{project}/stats/{stat}/edit', 'ProjectStatController@edit')->name('dashboard.projects.stats.edit');
    Route::put('projects/{project}/stats/{stat}', 'ProjectStatController@update')->name('dashboard.projects.stats.update');
    Route::delete('projects/{project}/stats/{stat}', 'ProjectStatController@destroy')->name('dashboard.projects.stats.destroy');


    Route::get('projects/{project}/stats/{stat}/statData', 'ProjectStatController@values')->name('dashboard.projects.stats.statData');
    Route::get('projects/{project}/stats/{stat}/statData/create', 'ProjectStatController@createValue')->name('dashboard.projects.stats.statData.create');
    Route::post('projects/{project}/stats/{stat}/statData', 'ProjectStatController@storeValue')->name('dashboard.projects.stats.statData.store');
    Route::get('projects/{project}/stats/{stat}/statData/{statData}/edit', 'ProjectStatController@editValue')->name('dashboard.projects.stats.statData.edit');
    Route::put('projects/{project}/stats/{stat}/statData/{statData}', 'ProjectStatController@updateValue')->name('dashboard.projects.stats.statData.update');
    Route::delete('projects/{project}/stats/{stat}/statData/{statData}', 'ProjectStatController@destroyValue')->name('dashboard.projects.stats.statData.destroy');


    Route::get('projects/{project}/subprojects', 'SubProjectController@index')->name('dashboard.projects.subProjects');
    Route::get('projects/{project}/subprojects/create', 'SubProjectController@create')->name('dashboard.projects.subProjects.create');
    Route::post('projects/{project}/subprojects', 'SubProjectController@store')->name('dashboard.projects.subProjects.store');
    Route::get('projects/{project}/subprojects/{subProject}/edit', 'SubProjectController@edit')->name('dashboard.projects.subProjects.edit');
    Route::put('projects/{project}/subprojects/{subProject}', 'SubProjectController@update')->name('dashboard.projects.subProjects.update');
    Route::delete('projects/{project}/subprojects/{subProject}', 'SubProjectController@destroy')->name('dashboard.projects.subProjects.destroy');


    Route::get('projects/{project}/subprojects/{subProject}/floorplansDestroy', 'SubProjectController@floorplansDestroy')->name('dashboard.subProjects.floorplansDestroy');
    Route::get('projects/{project}/subprojects/{subProject}/floorplan/{floorplan}/floorplanDestroy', 'SubProjectController@floorplanDestroy')->name('dashboard.subProjects.floorplanDestroy');


    Route::get('projects/{project}/subprojects/{subProject}/paymentPlans', 'SubProjectController@payments')->name('dashboard.projects.subProjects.paymentPlans');
    Route::get('projects/{project}/subprojects/{subProject}/create', 'SubProjectController@createPayment')->name('dashboard.projects.subProjects.paymentPlans.create');
    Route::post('projects/{project}/subprojects/{subProject}/paymentPlans', 'SubProjectController@storePayment')->name('dashboard.projects.subProjects.paymentPlans.store');
    Route::get('projects/{project}/subprojects/{subProject}/paymentPlans/{payment}/edit', 'SubProjectController@editPayment')->name('dashboard.projects.subProjects.paymentPlans.edit');
    Route::put('projects/{project}/subprojects/{subProject}/paymentPlans/{payment}', 'SubProjectController@updatePayment')->name('dashboard.projects.subProjects.paymentPlans.update');
    Route::delete('projects/{project}/subprojects/{subProject}/paymentPlans/{payment}', 'SubProjectController@destroyPayment')->name('dashboard.projects.subProjects.paymentPlans.destroy');



    Route::get('projects/{project}/subprojects/{subProject}/bedrooms', 'ProjectBedroomController@index')->name('dashboard.projects.subProjects.bedrooms');
    Route::get('projects/{project}/subprojects/{subProject}/bedrooms/create', 'ProjectBedroomController@create')->name('dashboard.projects.subProjects.bedrooms.create');
    Route::post('projects/{project}/subprojects{subProject}/bedrooms', 'ProjectBedroomController@store')->name('dashboard.projects.subProjects.bedrooms.store');
    Route::get('projects/{project}/subprojects/{subProject}/bedrooms/{bedroom}/edit', 'ProjectBedroomController@edit')->name('dashboard.projects.subProjects.bedrooms.edit');
    Route::put('projects/{project}/subprojects/{subProject}/bedrooms/{bedroom}', 'ProjectBedroomController@update')->name('dashboard.projects.subProjects.bedrooms.update');
    Route::delete('projects/{project}/subprojects/{subProject}/bedrooms/{bedroom}', 'ProjectBedroomController@destroy')->name('dashboard.projects.subProjects.bedrooms.destroy');


    Route::get('projects/{project}/subprojects/{subProject}/bedrooms/{bedroom}/specifications', 'ProjectBedroomController@specifications')->name('dashboard.projects.subProjects.bedrooms.specifications');
    Route::get('projects/{project}/subprojects/{subProject}/bedrooms/{bedroom}/specifications/create', 'ProjectBedroomController@createSpecification')->name('dashboard.projects.subProjects.bedrooms.specifications.create');
    Route::post('projects/{project}/subprojects{subProject}/bedrooms/{bedroom}/specifications', 'ProjectBedroomController@storeSpecification')->name('dashboard.projects.subProjects.bedrooms.specifications.store');
    Route::get('projects/{project}/subprojects/{subProject}/bedrooms/{bedroom}/specifications/{specification}/edit', 'ProjectBedroomController@editSpecification')->name('dashboard.projects.subProjects.bedrooms.specifications.edit');
    Route::put('projects/{project}/subprojects/{subProject}/bedrooms/{bedroom}/specifications/{specification}', 'ProjectBedroomController@updateSpecification')->name('dashboard.projects.subProjects.bedrooms.specifications.update');
    Route::delete('projects/{project}/subprojects/{subProject}/bedrooms/{bedroom}/specifications/{specification}', 'ProjectBedroomController@destroySpecification')->name('dashboard.projects.subProjects.bedrooms.specifications.destroy');

    Route::get('careers/allApplicants', 'CareerController@allApplicants')->name('dashboard.careers.allApplicants');
    Route::get('careers/applicants/{applicant}', 'CareerController@singleApplicant')->name('dashboard.careers.singleApplicant');
    Route::delete('careers/applicants/{applicant}', 'CareerController@deleteApplicant')->name('dashboard.careers.applicant.destroy');


    Route::resource('careers', CareerController::class, ['as' => 'dashboard']);
    Route::get('careers/{career}/applicants', 'CareerController@applicants')->name('dashboard.careers.applicants');
    Route::get('careers/{career}/applicants/{applicant}', 'CareerController@singleCareerApplicant')->name('dashboard.careers.applicant');



    Route::resource('banners', BannerController::class, ['as' => 'dashboard']);
    Route::resource('cronJobs', cronJobMainController::class, ['as' => 'dashboard']);
    Route::resource('counters', CounterController::class, ['as' => 'dashboard']);
    Route::resource('testimonials', TestimonialController::class, ['as' => 'dashboard']);
    Route::resource('languages', LanguageController::class, ['as' => 'dashboard']);
    Route::resource('services', ServiceController::class, ['as' => 'dashboard']);
    Route::resource('articles', ArticleController::class, ['as' => 'dashboard']);

    Route::get('articles/{article}/media/{media}', 'ArticleController@mediaDestroy')->name('dashboard.articles.media.delete');

    Route::resource('video-gallery', VideoGalleryController::class, ['as' => 'dashboard']);
    Route::resource('users', UserController::class, ['as' => 'dashboard']);

    Route::resource('leads', LeadController::class, ['as' => 'dashboard']);
    Route::post('leads/{lead}/moveToCRM', 'LeadController@moveToCRM')->name('dashboard.leads.moveToCRM');
    Route::get('profileSettings', 'ProfileSettingController@get')->name('dashboard.profileSettings');
    Route::put('profileSettings', 'ProfileSettingController@update')->name('dashboard.profileSettings.update');
    Route::get('bulk-sms', 'WebsiteSettingController@getSmsBulk')->name('dashboard.bulk-sms');
    Route::put('bulk-sms', 'WebsiteSettingController@updateSmsBulk')->name('dashboard.bulk-sms.update');
    Route::get('recaptcha-site-key', 'WebsiteSettingController@getRecaptchaSiteKey')->name('dashboard.recaptcha-site-key');
    Route::put('recaptcha-site-key', 'WebsiteSettingController@updateRecaptchaSiteKey')->name('dashboard.recaptcha-site-key.update');
    Route::get('social-info', 'WebsiteSettingController@getSocialInfo')->name('dashboard.social-info');
    Route::put('social-info', 'WebsiteSettingController@updateSocialInfo')->name('dashboard.social-info.update');
    Route::get('basic-info', 'WebsiteSettingController@getBasicInfo')->name('dashboard.basic-info');
    Route::put('basic-info', 'WebsiteSettingController@updateBasicInfo')->name('dashboard.basic-info.update');
    Route::resource('page-tags', PageTagController::class, ['as' => 'dashboard']);
});

Route::namespace('App\Http\Controllers\Frontend')->group(function () {
    Route::get('{slug}', 'HomeController@dynamicPage')->name('dynamicPage');
});

Route::match(['delete'], 'deletePaymentPlanAjax/{id}', 'App\Http\Controllers\Dashboard\ProjectController@deletePaymentPlanAjax')->name('deletePaymentPlanAjax');
