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
      ini_set('max_execution_time', 3000); // 300 seconds = 5 minutes
      set_time_limit(0);
        // $this->call(AmerPermissionSeeder::class);

        // $this->call(RolesTableSeeder::class);
        // $this->call(RoleUserTableSeeder::class);
        //$this->call(SapPersdataSeeder::class);

        $this->call(CompaniesTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(EmplSubgroupsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(UserRecordsTableSeeder::class);


    }
}
