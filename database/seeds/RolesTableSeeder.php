<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => 'All User',
                'created_by' => '41498',
                'updated_by' => '41498',
                'deleted_by' => NULL,
                'created_at' => '2020-03-16 14:39:29',
                'updated_at' => '2020-03-16 19:23:11',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'title' => 'Super Admin',
                'created_by' => '41498',
                'updated_by' => '41498',
                'deleted_by' => NULL,
                'created_at' => '2020-03-16 15:35:38',
                'updated_at' => '2020-03-16 15:44:52',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}