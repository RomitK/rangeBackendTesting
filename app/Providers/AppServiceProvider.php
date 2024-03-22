<?php

namespace App\Providers;

use App\Models\PageTag;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        Validator::extend('slug', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)$/', $value);
        });
        Paginator::useBootstrap();

        $pagemeta = null;
        $website_name = null;
        $logo = null;
        $favicon = null;
        $website_url = null;
        $slogan = null;
        $email = null;
        $website_description = null;
        $contact_number = null;
        $youtube = null;
        $twitter = null;
        $linkedin = null;
        $pinterest = null;
        $instagram = null;
        $facebook = null;
        $address = null;
        $website_keyword = null;
        $footer_logo = null;
        $whatsapp = null;
        $whatsapp_number = null;
        $tiktok = null;
        $copyright_description = null;
        $telephone_number = null;
        $tollfree_number = null;
        $address_longitude = null;
        $address_latitude = null;


        if (Schema::hasTable('website_settings')) {
            if (WebsiteSetting::where('key', 'logo')->exists()) {
                $logo =  WebsiteSetting::getLogo();
            }
            if (WebsiteSetting::where('key', 'favicon')->exists()) {
                $favicon = WebsiteSetting::getFavicon();
            }
            if (WebsiteSetting::where('key', 'footer_logo')->exists()) {
                $footer_logo = WebsiteSetting::getFooterLogo();
            }
            if (WebsiteSetting::where('key', 'website_name')->exists()) {
                View::share('name', WebsiteSetting::getSetting('website_name') ? WebsiteSetting::getSetting('website_name') : '');
                $website_name = WebsiteSetting::getSetting('website_name') ? WebsiteSetting::getSetting('website_name') : '';
            }
            if (WebsiteSetting::where('key', 'website_url')->exists()) {
                $website_url = WebsiteSetting::getSetting('website_url') ? WebsiteSetting::getSetting('website_url') : '';
            }
            if (WebsiteSetting::where('key', 'slogan')->exists()) {
                $slogan = WebsiteSetting::getSetting('slogan') ? WebsiteSetting::getSetting('slogan') : '';
            }
            if (WebsiteSetting::where('key', 'website_keyword')->exists()) {
                $website_keyword = WebsiteSetting::getSetting('website_keyword') ? WebsiteSetting::getSetting('website_keyword') : '';
            }
            if (WebsiteSetting::where('key', 'website_description')->exists()) {
                $website_description = WebsiteSetting::getSetting('website_description') ? WebsiteSetting::getSetting('website_description') : '';
            }
            if (WebsiteSetting::where('key', 'copyright_description')->exists()) {
                $copyright_description = WebsiteSetting::getSetting('copyright_description') ? WebsiteSetting::getSetting('copyright_description') : '';
            }
            if (WebsiteSetting::where('key', 'contact_number')->exists()) {
                $contact_number =  WebsiteSetting::getSetting('contact_number') ? WebsiteSetting::getSetting('contact_number') : '';
            }
            if (WebsiteSetting::where('key', 'whatsapp')->exists()) {
                $whatsapp =  WebsiteSetting::getSetting('whatsapp') ? WebsiteSetting::getSetting('whatsapp') : '';
            }
            if (WebsiteSetting::where('key', 'whatsapp_number')->exists()) {
                $whatsapp_number =  WebsiteSetting::getSetting('whatsapp_number') ? WebsiteSetting::getSetting('whatsapp_number') : '';
            }

            if (WebsiteSetting::where('key', 'telephone_number')->exists()) {
                $telephone_number =  WebsiteSetting::getSetting('telephone_number') ? WebsiteSetting::getSetting('telephone_number') : '';
            }
            if (WebsiteSetting::where('key', 'tollfree_number')->exists()) {
                $tollfree_number =  WebsiteSetting::getSetting('tollfree_number') ? WebsiteSetting::getSetting('tollfree_number') : '';
            }
            if (WebsiteSetting::where('key', 'email')->exists()) {
                $email =  WebsiteSetting::getSetting('email') ? WebsiteSetting::getSetting('email') : '';
            }
            if (WebsiteSetting::where('key', 'address')->exists()) {
                $address =  WebsiteSetting::getSetting('address') ? WebsiteSetting::getSetting('address') : '';
            }
            if (WebsiteSetting::where('key', 'facebook')->exists()) {
                $facebook  =  WebsiteSetting::getSetting('facebook') ? WebsiteSetting::getSetting('facebook') : '';
            }
            if (WebsiteSetting::where('key', 'instagram')->exists()) {
                $instagram =  WebsiteSetting::getSetting('instagram') ? WebsiteSetting::getSetting('instagram') : '';
            }

            if (WebsiteSetting::where('key', 'linkedin')->exists()) {
                $linkedin = WebsiteSetting::getSetting('linkedin') ? WebsiteSetting::getSetting('linkedin') : '';
            }
            if (WebsiteSetting::where('key', 'twitter')->exists()) {
                $twitter = WebsiteSetting::getSetting('twitter') ? WebsiteSetting::getSetting('twitter') : '';
            }
            if (WebsiteSetting::where('key', 'youtube')->exists()) {
                $youtube = WebsiteSetting::getSetting('youtube') ? WebsiteSetting::getSetting('youtube') : '';
            }
            if (WebsiteSetting::where('key', 'tiktok')->exists()) {
                $tiktok = WebsiteSetting::getSetting('tiktok') ? WebsiteSetting::getSetting('tiktok') : '';
            }

            if (WebsiteSetting::where('key', 'address_longitude')->exists()) {
                $address_longitude = WebsiteSetting::getSetting('address_longitude') ? WebsiteSetting::getSetting('address_longitude') : '';
            }
            if (WebsiteSetting::where('key', 'address_latitude')->exists()) {
                $address_latitude = WebsiteSetting::getSetting('address_latitude') ? WebsiteSetting::getSetting('address_latitude') : '';
            }
        }
        View::share([
            'website_name' => $website_name,
            'logo' => $logo,
            'favicon' => $favicon,
            'footer_logo' => $footer_logo,
            'website_url' => $website_url,
            'website_keyword' => $website_keyword,
            'slogan' => $slogan,
            'email' => $email,
            'whatsapp_number' => $whatsapp_number,
            'whatsapp' => $whatsapp,
            'website_description' => $website_description,
            'copyright_description' => $copyright_description,
            'contact_number' => $contact_number,
            'youtube' => $youtube,
            'twitter' => $twitter,
            'linkedin' => $linkedin,
            'instagram' => $instagram,
            'facebook' => $facebook,
            'tiktok' => $tiktok,
            'address' => $address,
            'telephone_number' => $telephone_number,
            'tollfree_number' => $tollfree_number,
            'address_longitude' => $address_longitude,
            'address_latitude' => $address_latitude,

        ]);

        if (Schema::hasTable('page_tags')) {
            if (PageTag::where('page_name', Route::currentRouteName())->exists()) {
                $pagemeta =  PageTag::where('page_name', Route::currentRouteName())->first();
                View::share('pagemeta', $pagemeta);
            }
        }
        View::share('pagemeta', $pagemeta);
    }
}
