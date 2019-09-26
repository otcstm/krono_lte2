<?php

use App\User;
use App\Role;
use App\Permission;
use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{
    public function run()
    {
        User::findOrFail(1)->roles()->sync(1);


        $admin_permissions = Permission::all();
        Role::findOrFail(1)->permissions()->attach($admin_permissions->pluck('id'));
    }
}
