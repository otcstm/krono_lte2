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
                'updated_by' => '43570',
                'deleted_by' => '43570',
                'created_at' => '2020-03-16 14:39:29',
                'updated_at' => '2020-05-04 16:18:47',
                'deleted_at' => '2020-05-04 16:18:47',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => 'Super Admin',
                'created_by' => '41498',
                'updated_by' => '43570',
                'deleted_by' => NULL,
                'created_at' => '2020-03-16 15:35:38',
                'updated_at' => '2020-04-24 10:55:54',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'title' => 'HCBD',
                'created_by' => '19021',
                'updated_by' => '39868',
                'deleted_by' => NULL,
                'created_at' => '2020-03-17 11:30:09',
                'updated_at' => '2020-05-14 16:14:30',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'title' => 'User Admin',
                'created_by' => '19021',
                'updated_by' => '39871',
                'deleted_by' => NULL,
                'created_at' => '2020-03-17 11:37:56',
                'updated_at' => '2020-05-15 08:39:05',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'title' => 'Schedule rule for Raya Aidil Fitri 2020',
                'created_by' => '43570',
                'updated_by' => '43570',
                'deleted_by' => '43570',
                'created_at' => '2020-04-02 16:46:58',
                'updated_at' => '2020-04-13 10:47:00',
                'deleted_at' => '2020-04-13 10:47:00',
            ),
            5 => 
            array (
                'id' => 6,
                'title' => 'Schedule  rule for Ramadhan Kareem Month',
                'created_by' => '43570',
                'updated_by' => '43570',
                'deleted_by' => '43570',
                'created_at' => '2020-04-02 17:18:27',
                'updated_at' => '2020-04-13 10:47:09',
                'deleted_at' => '2020-04-13 10:47:09',
            ),
            6 => 
            array (
                'id' => 7,
                'title' => 'Approver',
                'created_by' => '43570',
                'updated_by' => '43570',
                'deleted_by' => '43570',
                'created_at' => '2020-04-09 14:39:43',
                'updated_at' => '2020-04-09 15:08:17',
                'deleted_at' => '2020-04-09 15:08:17',
            ),
            7 => 
            array (
                'id' => 8,
                'title' => 'Approver',
                'created_by' => '43570',
                'updated_by' => '43570',
                'deleted_by' => '43570',
                'created_at' => '2020-04-09 15:14:43',
                'updated_at' => '2020-04-15 13:27:20',
                'deleted_at' => '2020-04-15 13:27:20',
            ),
            8 => 
            array (
                'id' => 9,
                'title' => 'SHIFT MANAGEMENT',
                'created_by' => '42182',
                'updated_by' => '42182',
                'deleted_by' => '42182',
                'created_at' => '2020-04-15 16:15:44',
                'updated_at' => '2020-04-16 15:33:28',
                'deleted_at' => '2020-04-16 15:33:28',
            ),
            9 => 
            array (
                'id' => 10,
                'title' => 'Schedule  rule for Ramadhan Kareem Month',
                'created_by' => '43570',
                'updated_by' => NULL,
                'deleted_by' => '43570',
                'created_at' => '2020-04-24 10:37:03',
                'updated_at' => '2020-04-24 10:38:56',
                'deleted_at' => '2020-04-24 10:38:56',
            ),
            10 => 
            array (
                'id' => 11,
                'title' => 'User 1',
                'created_by' => '39868',
                'updated_by' => '39868',
                'deleted_by' => '39868',
                'created_at' => '2020-05-14 16:11:57',
                'updated_at' => '2020-05-14 23:29:21',
                'deleted_at' => '2020-05-14 23:29:21',
            ),
            11 => 
            array (
                'id' => 12,
                'title' => 'User 1',
                'created_by' => '39868',
                'updated_by' => '39868',
                'deleted_by' => NULL,
                'created_at' => '2020-05-15 12:17:05',
                'updated_at' => '2020-05-15 12:17:34',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}