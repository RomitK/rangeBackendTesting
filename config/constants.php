<?php
return [
    'rentId'=>9,
    'buyId'=>8,
    'roles'=>[

    ],
    'home'=>[
        'name'=>'home',
        'title'=> 'Home',
        'route'=>'dashboard.pageContents.home-page',
        'url'=> 'dashboard/pageContents/home-page'
    ],
    'about'=>[
        'name'=> 'about-us',
        'title'=> 'About Us',
        'route'=>'dashboard.pageContents.about-page',
        'url'=> 'dashboard/pageContents/about-page'
    ],
    'ceo'=>[
        'name'=> 'ceo',
        'route'=>'dashboard.pageContents.about-page',
    ],
    'properties'=>[
        'name'=>'properties',
        'title'=> 'Properties',
        'route'=>'dashboard.pageContents.properties-page',
        'url'=> 'dashboard/pageContents/properties-page'
    ],
    'rent'=>[
        'name'=>'rent',
        'title'=> 'Rent',
        'route'=>'dashboard.pageContents.rent-page',
        'url'=> 'dashboard/pageContents/rent-page'
    ],
    'resale'=>[
        'name'=>'resale',
        'title'=> 'Resale',
        'route'=>'dashboard.pageContents.resale-page',
        'url'=> 'dashboard/pageContents/resale-page'
    ],
    'offPlan'=>[
        'name'=>'offPlan',
        'title'=> 'Off Plan',
        'route'=>'dashboard.pageContents.offPlan-page',
        'url'=> 'dashboard/pageContents/offPlan-page'
    ],
    'developers'=>[
        'name'=>'developers',
        'title'=> 'Developers',
        'route'=>'dashboard.pageContents.developers-page',
        'url'=> 'dashboard/pageContents/developers-page'
    ],
    'communities'=>[
        'name'=>'communities',
        'title'=> 'Communities',
        'route'=>'dashboard.pageContents.communities-page',
        'url'=> 'dashboard/pageContents/communities-page'
    ],
    'faqs'=>[
        'name'=>'faqs',
        'title'=> 'FAQS',
        'route'=>'dashboard.pageContents.faqs-page',
        'url'=> 'dashboard/pageContents/faqs-page'
    ],
    'buyerGuide'=>[
        'name'=>'buyerGuide',
        'title'=> 'Buyer`s Guide',
        'route'=>'dashboard.pageContents.buyerGuide-page',
        'url'=> 'dashboard/pageContents/buyerGuide-page'
    ],
    'dubaiGuide' => [
        'name'=>'dubaiGuide',
        'title'=> 'Dubai Guide',
        'route'=>'dashboard.pageContents.dubaiGuide-page',
        'url'=> 'dashboard/pageContents/dubaiGuide-page'
    ],
    'sellerGuide'=>[
        'name'=>'sellerGuide',
        'title'=> 'Seller`s Guide',
        'route'=>'dashboard.pageContents.sellerGuide-page',
        'url'=> 'dashboard/pageContents/sellerGuide-page'
    ],
    'relocatingToDubai'=>[
        'name'=>'relocatingToDubai',
        'title'=> 'Relocating To Dubai',
        'route'=>'dashboard.pageContents.relocatingToDubai-page',
        'url'=> 'dashboard/pageContents/relocatingToDubai-page'
    ],
    'termCondition'=>[
        'name'=>'termCondition',
        'title'=> 'Terms & Conditions ',
        'route'=>'dashboard.pageContents.termCondition-page',
        'url'=> 'dashboard/pageContents/termCondition-page'
    ],
    'privacyPolicy'=>[
        'name'=>'privacyPolicy',
        'title'=> 'Privacy Policy',
        'route'=>'dashboard.pageContents.privacyPolicy-page',
        'url'=> 'dashboard/pageContents/privacyPolicy-page'
    ],
    'factFigure'=>[
        'name'=>'factFigure',
        'title'=> 'Facts & Figures',
        'route'=>'dashboard.pageContents.factFigure-page',
        'url'=> 'dashboard/pageContents/factFigure-page'
    ],
    'aboutDubai'=>[
        'name'=>'aboutDubai',
        'title'=> 'About Dubai',
        'route'=>'dashboard.pageContents.aboutDubai-page',
        'url'=> 'dashboard/pageContents/aboutDubai-page'
    ],
    'whyInvest'=>[
        'name'=>'whyInvest',
        'title'=> 'Why Invest in Dubai',
        'route'=>'dashboard.pageContents.whyInvest-page',
        'url'=> 'dashboard/pageContents/whyInvest-page'
    ],

    'categories' => [
        'Sale' => '1',
        'Resale' => '2',
        'off-plan' => '3'
    ],
    'furnitueOption'=>[
        '0' => 'Furnished',
        '1'=>'Unfurnished',
        'partly'=> 'Partly furnished',
    ],
    'mediaTypes' => [
        'News',
        'Blogs',
        'Awards',
        'Celebrations',
        'Sales Event'
    ],
    'propertySources'=>[
        'crm',
        'xml'
    ],
    'propertySourcesWithAll'=>[
        ''=>'All',
        'CRM'=>'crm',
        'XML'=>'xml'
    ],
    
    'furnitueOptionWithAll'=>[
        ''=>'All',
        '0' => 'Furnished',
        '1'=>'Unfurnished',
        'partly'=> 'Partly furnished',
    ],
    
    'approvedWithAll'=>[
        ''=>'All',
        'requested'=> 'Requested',
        'approved'=>'Approved',
        'rejected'=>'Rejected'
    ],
    
    'approvedRejected'=>[
        
        'rejected'=>'Rejected',
        'approved'=>'Approved',
    ],
    'approvedRequested'=>[
        'requested'=> 'Requested',
    ],
    'requested'=>'requested',
    'approved'=>'approved',
    'rejected'=>'rejected',
    'super_admin' => 'super_admin',
    'admin' => 'admin',
    'user'=> 'user',
    'isAdmin' => ['super_admin', 'admin'],
    'statuses' => [
        'active' => 'Active',
        'Inactive' => 'Inactive'
    ],
    'statusesOption' => [
        ''=> 'All',
        'active' => 'Active',
        'Inactive' => 'Inactive'
    ],
    'accommodationType'=>[
        'Residential'=> 'Residential',
        'Commercial' => 'Commercial',
        'Both' => 'Both (Residential & Commercial)'
    ],
    'accommodationTypeR'=>[
        ''=>'All',
        'Residential'=> 'Residential',
        'Commercial' => 'Commercial',
        'Both' => 'Both (Residential & Commercial)'
    ],
    'booleanOptions'=>[
        '1'=>'Yes',
        '0'=>'No'
    ],
    'booleanOptionsWithAll'=>[
        '1'=>'Yes',
        '0'=>'No',
        'null'=> 'All',
    ],
    'listTypes'=>[
        'primary'=> 'Primary',
        'secondary'=> 'Secondary'
    ],
    'displayOptionWithAll'=>[
        ''=> 'All',
        '1'=>'Yes',
        '0'=>'No',
        
    ],
    
    'active' => 'active',
    'Inactive' => 'Inactive',
    'emirates' => [
        'Abu Dhabi',
        'Dubai',
        'Sharjah',
        'Ajman',
        'Umm Al Quwain',
        'Ras Al Khaimah',
        'Fujairah'
    ],
    'articleTypes'=> [
        'Blog',
        'News'
    ],
    'rating'=>[
        1,
        2,
        3,
        4,
        5,
    ],
    'tagTypes' => [
        'Developer'=>'Developer',
        'Community'=>'Community',
        'Project'=> 'Project'
    ],
    'career'=> 'career',
    'developer' => 'Developer',
    'community' => 'Community',
    'project' => 'Project',
    'Permissions' => [
        'leads'=>'Leads',
        'cronJobs'=>'Cronjobs',
        'real_estate'=>'Real Estate',
        'tags'=>'Tags',
        'teams'=> 'Teams',
        'awards'=>'Awards',
        'languages'=>'Languages',
        'content_management'=>'Content Management',
        'testimonials'=>'Testimonials',
        'services'=>'Services',
        'career_management'=>'Career Management',
        'seo'=>'SEO',
        'page_contents'=>'Page Contents',
        'user_management'=>'User Managements',
        'website_setting'=>'Website Settings',
        'xml_listings'=>'XML Listings',
        'offplan'=>'Off-Plan'
    ],
];

