<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => '1-nav-menu-admin',
                'descr' => 'Main Configuration Menu',
                'created_at' => '2020-04-23 20:26:11',
                'updated_at' => '2020-04-23 20:26:11',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'title' => '1-nav-admin',
                'descr' => 'Main Configuration ',
                'created_at' => '2020-04-23 20:26:11',
                'updated_at' => '2020-04-23 20:26:11',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'title' => '3-shift-grp',
                'descr' => 'Shift Group',
                'created_at' => '2020-04-23 20:26:11',
                'updated_at' => '2020-04-23 20:26:11',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'title' => '4-shift-plan',
                'descr' => 'Shift Assignment Planning Approval',
                'created_at' => '2020-04-23 20:26:11',
                'updated_at' => '2020-04-23 20:26:11',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'title' => '5-user-mngmt-menu',
                'descr' => 'User Management Menu',
                'created_at' => '2020-04-23 20:26:11',
                'updated_at' => '2020-04-23 20:26:11',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'title' => '5-user-mngmt',
                'descr' => 'User Management',
                'created_at' => '2020-04-23 20:26:11',
                'updated_at' => '2020-04-23 20:26:11',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'title' => '6-rpt-ot-menu',
            'descr' => 'Reports (User Admin) Menu',
                'created_at' => '2020-04-23 20:26:11',
                'updated_at' => '2020-04-23 20:26:11',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'title' => '6-rpt-ot',
            'descr' => 'Reports (UA)',
                'created_at' => '2020-04-23 20:26:11',
                'updated_at' => '2020-04-23 20:26:11',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'title' => '7-rpt-ot-sa-menu',
            'descr' => 'Reports (Sys Admin) Menu',
                'created_at' => '2020-04-23 20:26:11',
                'updated_at' => '2020-04-23 20:26:11',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'title' => '7-rpt-ot-sa',
            'descr' => 'Reports (SA)',
                'created_at' => '2020-04-23 20:26:11',
                'updated_at' => '2020-04-23 20:26:11',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}