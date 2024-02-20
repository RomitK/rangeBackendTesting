<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::insert([
            ['name' => 'English', 'status' => config('constants.active'), 'user_id'=> 1],
            ['name' => 'Arabic',  'status' => config('constants.active'), 'user_id'=> 1],
            ['name' => 'Urdu', 'status' => config('constants.active'), 'user_id'=> 1]
        ]);
    }
}
