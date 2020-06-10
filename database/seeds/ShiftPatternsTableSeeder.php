<?php

use Illuminate\Database\Seeder;

class ShiftPatternsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('shift_patterns')->delete();
        
        \DB::table('shift_patterns')->insert(array (
            0 => 
            array (
                'id' => 5912,
                'created_at' => '2020-04-09 17:32:30',
                'updated_at' => '2020-04-09 23:31:46',
                'code' => 'OFF1',
            'description' => 'Normal (Mon-Fri)',
                'created_by' => 43570,
                'days_count' => 7,
                'total_hours' => 44.5,
                'total_minutes' => 2670,
                'deleted_at' => NULL,
                'last_edited_by' => 43570,
                'deleted_by' => NULL,
                'is_weekly' => 1,
                'source' => 'OTCS',
            ),
            1 => 
            array (
                'id' => 5914,
                'created_at' => '2020-04-09 23:30:24',
                'updated_at' => '2020-04-09 23:47:09',
                'code' => 'OFF2',
            'description' => 'Normal (Sun-Thu)',
                'created_by' => 43570,
                'days_count' => 7,
                'total_hours' => 44.5,
                'total_minutes' => 2670,
                'deleted_at' => NULL,
                'last_edited_by' => 43570,
                'deleted_by' => NULL,
                'is_weekly' => 1,
                'source' => 'OTCS',
            ),
            2 => 
            array (
                'id' => 5915,
                'created_at' => '2020-04-09 23:36:47',
                'updated_at' => '2020-04-09 23:52:38',
                'code' => 'OFF3',
            'description' => 'Normal (Mon-Sat)',
                'created_by' => 43570,
                'days_count' => 7,
                'total_hours' => 46.75,
                'total_minutes' => 2805,
                'deleted_at' => NULL,
                'last_edited_by' => 43570,
                'deleted_by' => NULL,
                'is_weekly' => 1,
                'source' => 'OTCS',
            ),
            3 => 
            array (
                'id' => 7047,
                'created_at' => '2020-04-25 04:22:36',
                'updated_at' => '2020-05-05 05:48:29',
                'code' => 'Z_05',
                'description' => 'Shift NNNNORNN',
                'created_by' => 43570,
                'days_count' => 8,
                'total_hours' => 48.0,
                'total_minutes' => 2880,
                'deleted_at' => NULL,
                'last_edited_by' => 43570,
                'deleted_by' => NULL,
                'is_weekly' => 0,
                'source' => 'OTCS',
            ),
            4 => 
            array (
                'id' => 7049,
                'created_at' => '2020-04-25 09:04:18',
                'updated_at' => '2020-04-25 09:21:45',
                'code' => 'Z_01',
            'description' => 'Normal (Mon-Fri) - Waktu anjal (8.00am-5.00pm)',
                'created_by' => 43570,
                'days_count' => 7,
                'total_hours' => 45.0,
                'total_minutes' => 2700,
                'deleted_at' => NULL,
                'last_edited_by' => 43570,
                'deleted_by' => NULL,
                'is_weekly' => 1,
                'source' => 'OTCS',
            ),
            5 => 
            array (
                'id' => 7050,
                'created_at' => '2020-04-25 09:13:53',
                'updated_at' => '2020-04-25 09:17:14',
                'code' => 'Z_02',
            'description' => 'Normal (Mon-Fri) - Waktu anjal (9.00am-6.00pm)',
                'created_by' => 43570,
                'days_count' => 7,
                'total_hours' => 45.0,
                'total_minutes' => 2700,
                'deleted_at' => NULL,
                'last_edited_by' => 43570,
                'deleted_by' => NULL,
                'is_weekly' => 1,
                'source' => 'OTCS',
            ),
            6 => 
            array (
                'id' => 7052,
                'created_at' => '2020-05-03 09:12:06',
                'updated_at' => '2020-05-03 09:13:57',
                'code' => 'OF18',
                'description' => 'TEST',
                'created_by' => 43570,
                'days_count' => 7,
                'total_hours' => 63.0,
                'total_minutes' => 3780,
                'deleted_at' => NULL,
                'last_edited_by' => 43570,
                'deleted_by' => NULL,
                'is_weekly' => 1,
                'source' => 'OTCS',
            ),
            7 => 
            array (
                'id' => 7053,
                'created_at' => '2020-05-04 11:11:26',
                'updated_at' => '2020-05-04 11:32:54',
                'code' => 'Z06M',
            'description' => '3 cycle shift-Morning (MMMMMM0R)',
                'created_by' => 43570,
                'days_count' => 8,
                'total_hours' => 48.0,
                'total_minutes' => 2880,
                'deleted_at' => NULL,
                'last_edited_by' => 43570,
                'deleted_by' => NULL,
                'is_weekly' => 0,
                'source' => 'OTCS',
            ),
            8 => 
            array (
                'id' => 7054,
                'created_at' => '2020-05-04 11:12:20',
                'updated_at' => '2020-05-04 11:46:25',
                'code' => 'Z06A',
            'description' => '3 cycle shift-Afternoon (AAAAAA0R)',
                'created_by' => 43570,
                'days_count' => 8,
                'total_hours' => 48.0,
                'total_minutes' => 2880,
                'deleted_at' => NULL,
                'last_edited_by' => 43570,
                'deleted_by' => NULL,
                'is_weekly' => 0,
                'source' => 'OTCS',
            ),
            9 => 
            array (
                'id' => 7055,
                'created_at' => '2020-05-04 11:12:56',
                'updated_at' => '2020-05-04 11:36:34',
                'code' => 'Z06N',
            'description' => '3 cycle shift-Night (NNNNNN0R)',
                'created_by' => 43570,
                'days_count' => 8,
                'total_hours' => 48.0,
                'total_minutes' => 2880,
                'deleted_at' => NULL,
                'last_edited_by' => 43570,
                'deleted_by' => NULL,
                'is_weekly' => 0,
                'source' => 'OTCS',
            ),
            10 => 
            array (
                'id' => 7057,
                'created_at' => '2020-05-18 09:20:50',
                'updated_at' => '2020-05-18 09:21:43',
                'code' => 'Team1',
                'description' => 'Test day',
                'created_by' => 39868,
                'days_count' => 1,
                'total_hours' => 8.0,
                'total_minutes' => 480,
                'deleted_at' => NULL,
                'last_edited_by' => 39868,
                'deleted_by' => NULL,
                'is_weekly' => 1,
                'source' => 'OTCS',
            ),
            11 => 
            array (
                'id' => 7058,
                'created_at' => '2020-05-18 09:31:39',
                'updated_at' => '2020-05-18 09:32:52',
                'code' => 'OFF4',
            'description' => 'Normal (Sat - Thu)',
                'created_by' => 19021,
                'days_count' => 2,
                'total_hours' => 17.5,
                'total_minutes' => 1050,
                'deleted_at' => NULL,
                'last_edited_by' => 19021,
                'deleted_by' => NULL,
                'is_weekly' => 1,
                'source' => 'OTCS',
            ),
        ));
        
        
    }
}