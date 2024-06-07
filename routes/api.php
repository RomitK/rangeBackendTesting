<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// header('Access-Control-Allow-Origin:  *');
// header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
// header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/test', function () {
    return [
        'title' => 'Hello',
        'description' => 'description  description'
    ];
});
Route::namespace('App\Http\Controllers\API')->group(function () {

    Route::get('/sendSMS', 'HomeController@sendSMS');

    Route::get('/bankNames', 'HomeController@bankNames');
    Route::get('/mortageYears', 'HomeController@mortageYears');

    Route::get('dubaiGuideData', 'HomeController@dubaiGuideData');

    Route::get('guides', 'GuideController@allGuides');

    Route::get('sellerGuideData', 'HomeController@sellerGuideData');
    Route::get('/getHomeData', 'HomeController@getHomeData');
    Route::get('/homeData', 'HomeController@homeData');

    Route::get('/homeCommunities', 'CommunityController@getHomeCommunities');

    Route::get('/search', 'HomeController@search');
    Route::get('/searchR', 'HomeController@searchR');

    Route::get('/searchCount', 'HomeController@searchCount');

    Route::get('/propertyPageSearch', 'HomeController@propertyPageSearch');
    Route::get('/projectPageSearch', 'HomeController@projectPageSearch');

    Route::get('/faqs', 'FaqController@index');
    Route::get('/contactFaqs', 'FaqController@contactFaqs');
    Route::get('/communnityOptions', 'CommunityController@commnunityOptions');
    Route::get('/communities', 'CommunityController@index');
    Route::get('/communities/{slug}', 'CommunityController@singleCommunity');
    Route::get('/communities/{slug}/detail', 'CommunityController@singleCommunityDetail');
    Route::get('/communities/{slug}/meta', 'CommunityController@singleCommunityMeta');

    Route::get('/managements', 'AgentController@managements');
    Route::get('/managements/{slug}', 'AgentController@singleManagement');
    Route::get('/agents', 'AgentController@agents');


    Route::get('/managementLists', 'AgentController@managementLists');
    Route::get('/managements/{slug}/detail', 'AgentController@singleManagementDetail');
    Route::get('/managements/{slug}/meta', 'AgentController@singleManagementMeta');
    Route::get('/agentLists', 'AgentController@agentLists');
    Route::post('/checkEmployeeId', 'AgentController@checkEmployeeId');

    Route::get('/careers', 'CareerController@index');
    Route::get('/careers/{slug}', 'CareerController@singleCareer');
    Route::get('/careers/{slug}/meta', 'CareerController@singleCareerMeta');
    Route::get('/careers/{slug}/detail', 'CareerController@singleCareerDetail');
    Route::post('job-application', 'CareerController@saveJobApplication');
    Route::post('/careerForm', 'CareerController@careerForm')->name('careerForm');

    Route::get('/developers', 'DeveloperController@index');
    Route::get('/developerOptions', 'DeveloperController@developerOptions');
    Route::get('/developers/{slug}', 'DeveloperController@singleDeveloper');
    Route::get('/developers/{slug}/detail', 'DeveloperController@singleDeveloperDetail');
    Route::get('/developers/{slug}/meta', 'DeveloperController@singleDeveloperMeta');


    Route::get('/projects/priceList', 'ProjectController@priceList');
    Route::get('/projects/areaList', 'ProjectController@areaList');
    Route::get('/projects/{slug}', 'ProjectController@singleProject');
    Route::get('/projects/{slug}/detail', 'ProjectController@singleProjectDetail');
    Route::get('/projects/{slug}/nearByProjects', 'ProjectController@nearByProjects');
    Route::get('/projects/{slug}/meta', 'ProjectController@projectMeta');
    Route::any('/projects', 'ProjectController@projects');
    Route::any('/projectsList', 'ProjectController@projectsList');

    Route::get('/projectOptions', 'ProjectController@projectOptions');
    Route::get('/projectOfferTypes', 'ProjectController@projectOfferTypes');


    Route::get('/accommodations', 'AccommodationController@index');
    Route::get('/projectAccommodations', 'AccommodationController@projectAccommodations');
    Route::get('/propertyAccommodations', 'AccommodationController@propertyAccommodationLists');

    Route::get('/accommodationOptions', 'AccommodationController@accommodationOptions');
    Route::get('/communityAccommodationOptions', 'AccommodationController@communityAccommodationOptions');
    Route::get('/developerAccommodationOptions', 'AccommodationController@developerAccommodationOptions');


    Route::get('/amenities', 'AmenityController@index');
    Route::get('/propertyAmenities', 'AmenityController@propertyAmenities');
    Route::get('/projectAmenities', 'AmenityController@projectAmenities');
    Route::get('/properties/{type}/priceList', 'PropertyController@priceList');
    Route::get('/properties/{type}/areaList', 'PropertyController@areaList');


    Route::any('/properties', 'PropertyController@propertiesDemo')->name('properties-demos');
    Route::any('/propertiesList', 'PropertyController@properties');
    Route::any('/properties/{slug}', 'PropertyController@singleProperty');
    Route::any('/properties/{slug}/detail', 'PropertyController@singlePropertyDetail');
    Route::any('/properties/{slug}/meta', 'PropertyController@singlePropertyMeta');

    Route::any('/properties/{slug}/detailR', 'PropertyController@singlePropertyDetailR');

    Route::get('/awards', 'AwardController@index');
    Route::get('/medias', 'MediaController@index');
    Route::get('/medias/{slug}/detail', 'MediaController@singleMediaDetail');
    Route::get('/medias/{slug}/meta', 'MediaController@singleMediaMeta');


    Route::post('/contactUs', 'HomeController@contactUsForm')->name('contact-us');
    Route::post('/sendOtp', 'HomeController@sendOtp');
    Route::post('/verifyOtp', 'HomeController@verifyOtp');

    Route::get('/meta/{pageName}', 'MetaController@homeMeta');
});

Route::namespace('App\Http\Controllers\Frontend')->group(function () {
    Route::post('/storeData', 'HomeController@storeData')->name('storeData');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
