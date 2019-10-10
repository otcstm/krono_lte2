<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AmerPermissionSeeder::class);
<<<<<<< HEAD
=======

>>>>>>> 292f9cc7dd7a1feaa2825a49c91a43e69196790f
        // $this->call(RolesTableSeeder::class);
        // $this->call(RoleUserTableSeeder::class);
        $this->call(SapPersdataSeeder::class);
    }
}
