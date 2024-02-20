<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TagCategory;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TagCategory::insert([
            ['name' => 'Villa', 'type'=>config('constants.community'), 'status' => config('constants.active'), 'user_id'=> 1],
            ['name' => 'Apartment', 'type'=>config('constants.community'), 'status' => config('constants.active'), 'user_id'=> 1],
            ['name' => 'Luxury Apartments','type'=>config('constants.community'), 'status' => config('constants.active'), 'user_id'=> 1],
            ['name' => 'Latest Launches','type'=>config('constants.community'),  'status' => config('constants.active'), 'user_id'=> 1],
            ['name' => 'Beachfront Properties','type'=>config('constants.community'),'status' => config('constants.active'),  'user_id'=> 1]
        ]);
    }
}
