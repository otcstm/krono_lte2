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
                'permission_id' => 5,
            ),
            3 => 
            array (
                'role_id' => 2,
                'permission_id' => 6,
            ),
            4 => 
            array (
                'role_id' => 2,
                'permission_id' => 9,
            ),
            5 => 
            array (
                'role_id' => 2,
                'permission_id' => 10,
            ),
            6 => 
            array (
                'role_id' => 4,
                'permission_id' => 7,
            ),
            7 => 
            array (
                'role_id' => 3,
                'permission_id' => 3,
            ),
            8 => 
            array (
                'role_id' => 3,
                'permission_id' => 7,
            ),
            9 => 
            array (
                'role_id' => 3,
                'permission_id' => 8,
            ),
            10 => 
            array (
                'role_id' => 10,
                'permission_id' => 1,
            ),
            11 => 
            array (
                'role_id' => 10,
                'permission_id' => 2,
            ),
            12 => 
            array (
                'role_id' => 1,
                'permission_id' => 4,
            ),
            13 => 
            array (
                'role_id' => 11,
                'permission_id' => 3,
            ),
            14 => 
            array (
                'role_id' => 11,
                'permission_id' => 1,
            ),
            15 => 
            array (
                'role_id' => 4,
                'permission_id' => 8,
            ),
            16 => 
            array (
                'role_id' => 12,
                'permission_id' => 1,
            ),
            17 => 
            array (
                'role_id' => 12,
                'permission_id' => 3,
            ),
            18 => 
            array (
                'role_id' => 12,
                'permission_id' => 8,
            ),
        ));
        
        
    }
}