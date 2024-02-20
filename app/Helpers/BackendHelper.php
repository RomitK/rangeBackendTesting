<?php
if(!function_exists('activeChildNavBar')){
    function activeParentNavBar($parentNav, $className)
    {

        if($parentNav == 'realEstate'){
            $childElements= [

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
                'dashboard.agents',
                'dashboard.completion-statuses',
                'dashboard.categories',
                'dashboard.communities',
                'dashboard.subCommunities',
                'dashboard.floorPlans',
            ];

        }elseif($parentNav == 'leadManagement'){
            $childElements= [
                'dashboard.leads',
            ];
        }elseif($parentNav == 'contentManagement'){
            $childElements= [
                'dashboard.articles',
                'dashboard.latestNews',
                'dashboard.video-gallery'
            ];
        }elseif($parentNav == 'websiteSettings'){
            $childElements= [
                'dashboard.bulk-sms',
                'dashboard.recaptcha-site-key',
                'dashboard.social-info',
                'dashboard.basic-info'
            ];
        }elseif($parentNav == 'SEO'){
            $childElements= [
                'dashboard.page-tags',
            ];
        }elseif($parentNav == 'userManagement'){
            $childElements= [
                'dashboard.users',
                'dashboard.roles',
            ];
        }elseif($parentNav == 'SEO'){
            $childElements= [
                'dashboard.page-tags',
            ];
        }
        elseif($parentNav == 'pageContents'){

            $childElements= [
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

        foreach($childElements as $child){

            if(str_contains(Route::currentRouteName(), $child) == 1)
            {
                return $className;
            }
        }
    }
}
if(!function_exists('activeChildNavBar')){
    function activeChildNavBar($routeName)
    {
        return (str_contains(Route::currentRouteName(), $routeName) == 1) ? 'active' : '';
    }
}
if(!function_exists('getFrontentRouteInfo')){
    function getFrontentRouteInfo()
    {
        $frontendRoutes = [];
        $allRoutes = Route::getRoutes();
        foreach( $allRoutes as $key=>$route){
            if($route->action['namespace'] == 'App\Http\Controllers\Frontend'){
                array_push($frontendRoutes,$route);
            }
        }
        return $frontendRoutes;
    }
}

function get_pagination(){

  $pagination = [
                  25  => 'Show up to 25 items',
                  50  => '50 items',
                  100 => '100 items',
                  200 => '200 items',
                  500 => '500 items'
                ];
  return $pagination;
}
?>
