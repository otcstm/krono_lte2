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
        // $this->call(AmerPermissionSeeder::class);

        $this->call(RolesTableSeeder::class);
        // $this->call(RoleUserTableSeeder::class);

        $this->call(CompaniesTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(EmplSubgroupsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(UserRecordsTableSeeder::class);

        $this->call(PsubareasTableSeeder::class);
        $this->call(DayTypesTableSeeder::class);
        $this->call(ShiftPatternsTableSeeder::class);
        $this->call(OvertimeFormulasTableSeeder::class);
        $this->call(OvertimeEligibilitiesTableSeeder::class);
        $this->call(OvertimeExpiriesTableSeeder::class);
        $this->call(CostcentersTableSeeder::class);

        $this->call(HolidayCalendarsTableSeeder::class);
        $this->call(HolidayLogsTableSeeder::class);
        $this->call(HolidaysTableSeeder::class);
        $this->call(SetupCodesTableSeeder::class);

        // $this->call(PaymentSchedulesTableSeeder::class);
        $this->call(ShiftPatternDaysTableSeeder::class);


     $this->call(ProjectsTableSeeder::class);
       $this->call(MaintenanceOrdersTableSeeder::class);
       $this->call(InternalOrdersTableSeeder::class);
      $this->call(PermissionsTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        //$this->call(AnnouncementTableSeeder::class);
    }
}
