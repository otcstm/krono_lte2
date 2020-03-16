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
                'role_id' => 1,
                'permission_id' => 5,
            ),
            1 => 
            array (
                'role_id' => 2,
                'permission_id' => 1,
            ),
            2 => 
            array (
                'role_id' => 2,
                'permission_id' => 7,
            ),
            3 => 
            array (
                'role_id' => 2,
                'permission_id' => 17,
            ),
            4 => 
            array (
                'role_id' => 1,
                'permission_id' => 3,
            ),
            5 => 
            array (
                'role_id' => 1,
                'permission_id' => 2,
            ),
            6 => 
            array (
                'role_id' => 1,
                'permission_id' => 20,
            ),
            7 => 
            array (
                'role_id' => 1,
                'permission_id' => 23,
            ),
            8 => 
            array (
                'role_id' => 1,
                'permission_id' => 24,
            ),
            9 => 
            array (
                'role_id' => 1,
                'permission_id' => 36,
            ),
        ));
        
        
    }
}