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
                'id' => 2,
                'created_at' => '2019-11-08 12:32:38',
                'updated_at' => '2019-11-11 12:43:49',
                'code' => 'OFF1',
            'description' => 'Normal (Mon-Fri)',
                'created_by' => 43570,
                'days_count' => 5,
                'total_hours' => 44.5,
                'total_minutes' => 2670,
                'deleted_at' => NULL,
                'last_edited_by' => 43570,
                'deleted_by' => NULL,
                'is_weekly' => 0,
            ),
            1 => 
            array (
                'id' => 3,
                'created_at' => '2019-11-11 13:07:36',
                'updated_at' => '2019-11-11 13:10:09',
                'code' => 'OFF2',
            'description' => 'Normal (Sun-Thu)',
                'created_by' => 43570,
                'days_count' => 7,
                'total_hours' => 44.5,
                'total_minutes' => 2670,
                'deleted_at' => NULL,
                'last_edited_by' => 43570,
                'deleted_by' => NULL,
                'is_weekly' => 0,
            ),
            2 => 
            array (
                'id' => 4,
                'created_at' => '2019-11-11 15:30:51',
                'updated_at' => '2019-11-11 15:41:22',
                'code' => 'OFF3',
            'description' => 'Normal (Mon-Sat)',
                'created_by' => 43570,
                'days_count' => 7,
                'total_hours' => 46.75,
                'total_minutes' => 2805,
                'deleted_at' => NULL,
                'last_edited_by' => 43570,
                'deleted_by' => NULL,
                'is_weekly' => 0,
            ),
            3 => 
            array (
                'id' => 5,
                'created_at' => '2019-11-11 15:49:07',
                'updated_at' => '2019-11-11 15:59:08',
                'code' => 'OFF4',
            'description' => 'Normal (Sat-Thu)',
                'created_by' => 43570,
                'days_count' => 7,
                'total_hours' => 45.75,
                'total_minutes' => 2745,
                'deleted_at' => NULL,
                'last_edited_by' => 43570,
                'deleted_by' => NULL,
                'is_weekly' => 0,
            ),
            4 => 
            array (
                'id' => 6,
                'created_at' => '2019-11-11 16:00:16',
                'updated_at' => '2019-11-11 16:04:26',
                'code' => 'OFF5',
            'description' => 'Normal (Wed-Sun)',
                'created_by' => 43570,
                'days_count' => 7,
                'total_hours' => 44.5,
                'total_minutes' => 2670,
                'deleted_at' => NULL,
                'last_edited_by' => 43570,
                'deleted_by' => NULL,
                'is_weekly' => 0,
            ),
            5 => 
            array (
                'id' => 7,
                'created_at' => '2019-11-11 16:06:01',
                'updated_at' => '2019-11-11 16:06:43',
                'code' => 'OFF6',
            'description' => 'Normal (Mon-Fri)',
                'created_by' => 43570,
                'days_count' => 4,
                'total_hours' => 36.0,
                'total_minutes' => 2160,
                'deleted_at' => NULL,
                'last_edited_by' => 43570,
                'deleted_by' => NULL,
                'is_weekly' => 0,
            ),
            6 => 
            array (
                'id' => 8,
                'created_at' => '2019-11-12 16:23:48',
                'updated_at' => '2019-11-12 16:23:59',
                'code' => 'Z_01',
            'description' => 'Normal (Mon-Fri) 8.00 am-5.00 pm',
                'created_by' => 43570,
                'days_count' => 0,
                'total_hours' => 0.0,
                'total_minutes' => 0,
                'deleted_at' => NULL,
                'last_edited_by' => 43570,
                'deleted_by' => NULL,
                'is_weekly' => 0,
            ),
            7 => 
            array (
                'id' => 9,
                'created_at' => '2019-11-12 16:24:42',
                'updated_at' => '2019-11-12 16:24:42',
                'code' => 'Z_02',
            'description' => 'Normal (Mon-Fri) 9.00 am-6.00 pm',
                'created_by' => 43570,
                'days_count' => 0,
                'total_hours' => 0.0,
                'total_minutes' => 0,
                'deleted_at' => NULL,
                'last_edited_by' => NULL,
                'deleted_by' => NULL,
                'is_weekly' => 0,
            ),
            8 => 
            array (
                'id' => 10,
                'created_at' => '2019-11-12 16:48:13',
                'updated_at' => '2019-11-12 16:53:49',
                'code' => 'Z_03',
            'description' => 'Shift MMMMOR (12 hours)',
                'created_by' => 43570,
                'days_count' => 0,
                'total_hours' => 0.0,
                'total_minutes' => 0,
                'deleted_at' => NULL,
                'last_edited_by' => 43570,
                'deleted_by' => NULL,
                'is_weekly' => 0,
            ),
            9 => 
            array (
                'id' => 11,
                'created_at' => '2019-11-12 16:54:19',
                'updated_at' => '2019-11-12 16:54:19',
                'code' => 'Z_04',
            'description' => 'Shift MMMMMMOR (8 hours)',
                'created_by' => 43570,
                'days_count' => 0,
                'total_hours' => 0.0,
                'total_minutes' => 0,
                'deleted_at' => NULL,
                'last_edited_by' => NULL,
                'deleted_by' => NULL,
                'is_weekly' => 0,
            ),
            10 => 
            array (
                'id' => 12,
                'created_at' => '2019-11-12 17:07:17',
                'updated_at' => '2019-11-12 17:07:17',
                'code' => 'Z_05',
            'description' => 'Shift AAAAAAR (8 hours)',
                'created_by' => 43570,
                'days_count' => 0,
                'total_hours' => 0.0,
                'total_minutes' => 0,
                'deleted_at' => NULL,
                'last_edited_by' => NULL,
                'deleted_by' => NULL,
                'is_weekly' => 0,
            ),
        ));
        
        
    }
}