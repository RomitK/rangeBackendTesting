<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::insert([
            ['name' => 'Rent', 'status' => config('constants.active'), 'user_id'=> 1],
            ['name' => 'Resale', 'status' => config('constants.active'), 'user_id'=> 1],
        ]);
    }
}
