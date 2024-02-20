<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsiteSetting;

class WebsiteSettingController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.testimonials'),
        ['only' => ['getSmsBulk','updateSmsBulk', 'getRecaptchaSiteKey', 'updateRecaptchaSiteKey', 'getSocialInfo',
        'updateSocialInfo','getBasicInfo','updateBasicInfo', 'update']
        ]);
    }
    public function getSmsBulk()
    {
        $bulk_sms_api_key = WebsiteSetting::getSetting('bulk_sms_api_key')? WebsiteSetting::getSetting('bulk_sms_api_key') : '';
        $bulk_sms_application_id = WebsiteSetting::getSetting('bulk_sms_application_id')? WebsiteSetting::getSetting('bulk_sms_application_id') : '';
        $bulk_sms_message_id = WebsiteSetting::getSetting('bulk_sms_message_id')? WebsiteSetting::getSetting('bulk_sms_message_id') : '';
        $bulk_sms_sender_id = WebsiteSetting::getSetting('bulk_sms_sender_id')? WebsiteSetting::getSetting('bulk_sms_sender_id') : '';
        return view('dashboard.websiteSettings.bulkSms', compact([
            'bulk_sms_api_key',
            'bulk_sms_application_id',
            'bulk_sms_message_id',
            'bulk_sms_sender_id'
        ]));
    }
    public function updateSmsBulk(Request $request)
    {
        $this->update($request);
        return redirect()->route('dashboard.bulk-sms')->with('success','Bulk SMS Setting has been updated successfully');
    }
    public function getRecaptchaSiteKey()
    {
        $recaptcha_site_id = WebsiteSetting::getSetting('recaptcha_site_id')? WebsiteSetting::getSetting('recaptcha_site_id') : '';
        $recaptcha_secret_key = WebsiteSetting::getSetting('recaptcha_secret_key')? WebsiteSetting::getSetting('recaptcha_secret_key') : '';

        return view('dashboard.websiteSettings.recaptchaSite', compact([
            'recaptcha_site_id',
            'recaptcha_secret_key'
        ]));
    }
    public function updateRecaptchaSiteKey(Request $request)
    {
        $this->update($request);
        return redirect()->route('dashboard.recaptcha-site-key')->with('success','Recaptcha Site Key Setting Setting has been updated successfully');
    }
    public function getSocialInfo()
    {
        $email = WebsiteSetting::getSetting('email')? WebsiteSetting::getSetting('email') : '';
        $contact_number = WebsiteSetting::getSetting('contact_number')? WebsiteSetting::getSetting('contact_number') : '';
        $telephone_number = WebsiteSetting::getSetting('telephone_number')? WebsiteSetting::getSetting('telephone_number') : '';
        $tollfree_number = WebsiteSetting::getSetting('tollfree_number')? WebsiteSetting::getSetting('tollfree_number') : '';
        $whatsapp = WebsiteSetting::getSetting('whatsapp')? WebsiteSetting::getSetting('whatsapp') : '';
        $whatsapp_number = WebsiteSetting::getSetting('whatsapp_number')? WebsiteSetting::getSetting('whatsapp_number') : '';
        $facebook = WebsiteSetting::getSetting('facebook')? WebsiteSetting::getSetting('facebook') : '';
        $twitter = WebsiteSetting::getSetting('twitter')? WebsiteSetting::getSetting('twitter') : '';
        $linkedin = WebsiteSetting::getSetting('linkedin')? WebsiteSetting::getSetting('linkedin') : '';
        $instagram = WebsiteSetting::getSetting('instagram')? WebsiteSetting::getSetting('instagram') : '';
        $youtube = WebsiteSetting::getSetting('youtube')? WebsiteSetting::getSetting('youtube') : '';
        $tiktok = WebsiteSetting::getSetting('tiktok')? WebsiteSetting::getSetting('tiktok') : '';


        return view('dashboard.websiteSettings.socialInfo', compact([
            'email',
            'contact_number',
            'telephone_number',
            'tollfree_number',
            'whatsapp_number',
            'whatsapp',
            'facebook',
            'twitter',
            'linkedin',
            'instagram',
            'youtube',
            'tiktok',

        ]));
    }
    public function updateSocialInfo(Request $request)
    {
        $this->update($request);
        return redirect()->route('dashboard.social-info')->with('success','Social Info Setting has been updated successfully');
    }
    public function getBasicInfo()
    {
        $website_name = WebsiteSetting::getSetting('website_name')? WebsiteSetting::getSetting('website_name') : '';
        $slogan = WebsiteSetting::getSetting('slogan')? WebsiteSetting::getSetting('slogan') : '';
        $website_url = WebsiteSetting::getSetting('website_url')? WebsiteSetting::getSetting('website_url') : '';
        $location = WebsiteSetting::getSetting('location')? WebsiteSetting::getSetting('location') : '';
        $address = WebsiteSetting::getSetting('address')? WebsiteSetting::getSetting('address') : '';
        $website_keyword = WebsiteSetting::getSetting('website_keyword')? WebsiteSetting::getSetting('website_keyword') : '';
        $copyright_description = WebsiteSetting::getSetting('copyright_description')? WebsiteSetting::getSetting('copyright_description') : '';
        $website_description = WebsiteSetting::getSetting('website_description')? WebsiteSetting::getSetting('website_description') : '';
        $address_latitude = WebsiteSetting::getSetting('address_latitude')? WebsiteSetting::getSetting('address_latitude') : '';
        $address_longitude = WebsiteSetting::getSetting('address_longitude')? WebsiteSetting::getSetting('address_longitude') : '';

        return view('dashboard.websiteSettings.basicInfo', compact([
            'website_name',
            'slogan',
            'address',
            'website_url',
            'website_description',
            'website_keyword',
            'copyright_description',
            'location',
            'address_latitude',
            'address_longitude'
        ]));
    }
    public function updateBasicInfo(Request $request)
    {
        $this->update($request);
        return redirect()->route('dashboard.basic-info')->with('success','Basic Info Setting has been updated successfully');
    }
    public function update($request)
    {
        foreach ($request->except(['_token', '_method']) as $key => $value)
        {
            if($key === 'logo' || $key === 'favicon' || $key === 'footer_logo'){

                $setting = WebsiteSetting::where('key', $key)->first();
                if ($request->hasFile('logo')) {
                     $setting->clearMediaCollection('logos');
                    $setting->addMediaFromRequest('logo')->toMediaCollection('logos');
                    $value = WebsiteSetting::getLogo();
                }
                if ($request->hasFile('favicon')) {
                      $setting->clearMediaCollection('favicons');
                    $setting->addMediaFromRequest('favicon')->toMediaCollection('favicons');
                    $value = WebsiteSetting::getFavicon();
                }
                if ($request->hasFile('footer_logo')) {
                      $setting->clearMediaCollection('footer_logos');
                    $setting->addMediaFromRequest('footer_logo')->toMediaCollection('footer_logos');
                    $value = WebsiteSetting::getFooterLogo();
                }
            }
            WebsiteSetting::setSetting($key,$value);
        }
        return true;
    }
}
