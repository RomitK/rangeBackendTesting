<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'Real Estate',
            'Tags',
            'Awards',
            'Languages',
            'Content Management',
            'Testimonials',
            'Services',
            'Career Management',
            'SEO',
            'Page Contents',
            'User Managements',
            'Website Settings',
            'Leads',
            'Cronjobs',
            'Off-Plan',
            'XML Listing',
        ];
        foreach($permissions as $permission)
        {
            Permission::create(['name'=>$permission]);
        }
    }
}
