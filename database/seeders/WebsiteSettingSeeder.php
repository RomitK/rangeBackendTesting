<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{WebsiteSetting, User};

class WebsiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();
        $settings = [
            'logo'=>'',
            'website_name'=>'Website Name',
            'footer_logo'=>'',
            'favicon'=>'',
            'footer_logo'=>'',
            'whatsapp'=>'',
            'copyright_description'=>'',
            'tiktok'=>'',
            'telephone_number'=>'',
            'tollfree_number'=>''
        ];
        foreach($settings as $key => $value)
        {
            WebsiteSetting::setSetting($key,$value, $user->id);
        }

    }
}
