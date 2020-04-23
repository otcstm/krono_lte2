<?php

use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permission_role')->delete();
        
        \DB::table('permission_role')->insert(array (
            0 => 
            array (
                'role_id' => 2,
                'permission_id' => 2,
            ),
            1 => 
            array (
                'role_id' => 2,
                'permission_id' => 1,
            ),
            2 => 
            array (
                'role_id' => 2,
                'permission_id' => 3,
            ),
            3 => 
            array (
                'role_id' => 2,
                'permission_id' => 4,
            ),
            4 => 
            array (
                'role_id' => 2,
                'permission_id' => 5,
            ),
            5 => 
            array (
                'role_id' => 2,
                'permission_id' => 6,
            ),
            6 => 
            array (
                'role_id' => 2,
                'permission_id' => 7,
            ),
            7 => 
            array (
                'role_id' => 2,
                'permission_id' => 8,
            ),
            8 => 
            array (
                'role_id' => 2,
                'permission_id' => 9,
            ),
            9 => 
            array (
                'role_id' => 2,
                'permission_id' => 10,
            ),
            10 => 
            array (
                'role_id' => 3,
                'permission_id' => 10,
            ),
        ));
        
        
    }
}