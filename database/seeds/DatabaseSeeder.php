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
      ini_set("memory_limit","256M");
      ini_set('max_execution_time', 3000); // 300 seconds = 5 minutes
      set_time_limit(0);
<<<<<<< HEAD
        $this->call(AmerPermissionSeeder::class);
=======
        // $this->call(AmerPermissionSeeder::class);
>>>>>>> 10f9f041136deb7fdb160c22aff52c3bc68d23cb

        // $this->call(RolesTableSeeder::class);
        // $this->call(RoleUserTableSeeder::class);

        $this->call(CompaniesTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(EmplSubgroupsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(UserRecordsTableSeeder::class);


        $this->call(PsubareasTableSeeder::class);
    }
}
