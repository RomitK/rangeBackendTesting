<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class AdminSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name'=>'Super Admin']);
        $permissionIds = Permission::pluck('id');
        $user = User::create([
            'email' => 'admin@gmail.com',
            'name' => 'Super Admin',
            'role' => config('constants.super_admin'),
            'status' => 'active',
            'password' => Hash::make('12345678'),
            'email_verified_At' => (\Carbon\Carbon::now()),
        ]);
        $user->assignRole($role->id);
        $role->syncPermissions($permissionIds);
    }
}
