<?php

use Illuminate\Database\Seeder;

class SetupCodesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('setup_codes')->delete();
        
        \DB::table('setup_codes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'item1' => 'ot_status',
                'item2' => 'D1',
                'item3' => 'Draft',
                'item4' => NULL,
                'item5' => NULL,
                'item6' => NULL,
                'item7' => NULL,
                'item8' => NULL,
                'item9' => NULL,
                'item10' => NULL,
                'start_date' => NULL,
                'end_date' => NULL,
                'created_by' => 53062,
                'created_at' => '2020-01-15 12:37:40',
                'updated_at' => '2020-01-15 12:37:54',
            ),
            1 => 
            array (
                'id' => 2,
                'item1' => 'ot_status',
                'item2' => 'D2',
                'item3' => 'Draft',
                'item4' => NULL,
                'item5' => NULL,
                'item6' => NULL,
                'item7' => NULL,
                'item8' => NULL,
                'item9' => NULL,
                'item10' => NULL,
                'start_date' => NULL,
                'end_date' => NULL,
                'created_by' => 53062,
                'created_at' => '2020-01-15 12:37:42',
                'updated_at' => '2020-01-15 12:37:55',
            ),
            2 => 
            array (
                'id' => 3,
                'item1' => 'ot_status',
                'item2' => 'Q1',
                'item3' => 'Query',
                'item4' => NULL,
                'item5' => NULL,
                'item6' => NULL,
                'item7' => NULL,
                'item8' => NULL,
                'item9' => NULL,
                'item10' => NULL,
                'start_date' => NULL,
                'end_date' => NULL,
                'created_by' => 53062,
                'created_at' => '2020-01-15 12:37:43',
                'updated_at' => '2020-01-15 12:37:56',
            ),
            3 => 
            array (
                'id' => 4,
                'item1' => 'ot_status',
                'item2' => 'Q2',
                'item3' => 'Query',
                'item4' => NULL,
                'item5' => NULL,
                'item6' => NULL,
                'item7' => NULL,
                'item8' => NULL,
                'item9' => NULL,
                'item10' => NULL,
                'start_date' => NULL,
                'end_date' => NULL,
                'created_by' => 53062,
                'created_at' => '2020-01-15 12:37:44',
                'updated_at' => '2020-01-15 12:37:57',
            ),
            4 => 
            array (
                'id' => 5,
                'item1' => 'ot_status',
                'item2' => 'PA',
                'item3' => 'Pending Approval',
                'item4' => NULL,
                'item5' => NULL,
                'item6' => NULL,
                'item7' => NULL,
                'item8' => NULL,
                'item9' => NULL,
                'item10' => NULL,
                'start_date' => NULL,
                'end_date' => NULL,
                'created_by' => 53062,
                'created_at' => '2020-01-15 12:37:45',
                'updated_at' => '2020-01-15 12:37:58',
            ),
            5 => 
            array (
                'id' => 6,
                'item1' => 'ot_status',
                'item2' => 'PV',
                'item3' => 'Pending Verification',
                'item4' => NULL,
                'item5' => NULL,
                'item6' => NULL,
                'item7' => NULL,
                'item8' => NULL,
                'item9' => NULL,
                'item10' => NULL,
                'start_date' => NULL,
                'end_date' => NULL,
                'created_by' => 53062,
                'created_at' => '2020-01-15 12:37:47',
                'updated_at' => '2020-01-15 12:37:59',
            ),
            6 => 
            array (
                'id' => 7,
                'item1' => 'ot_status',
                'item2' => 'A',
                'item3' => 'Approved',
                'item4' => NULL,
                'item5' => NULL,
                'item6' => NULL,
                'item7' => NULL,
                'item8' => NULL,
                'item9' => NULL,
                'item10' => NULL,
                'start_date' => NULL,
                'end_date' => NULL,
                'created_by' => 53062,
                'created_at' => '2020-01-15 12:37:48',
                'updated_at' => '2020-01-15 12:38:01',
            ),
            7 => 
            array (
                'id' => 8,
                'item1' => 'region',
                'item2' => 'SEM',
                'item3' => 'Semenanjung',
                'item4' => NULL,
                'item5' => NULL,
                'item6' => NULL,
                'item7' => NULL,
                'item8' => NULL,
                'item9' => NULL,
                'item10' => NULL,
                'start_date' => NULL,
                'end_date' => NULL,
                'created_by' => 53062,
                'created_at' => '2020-01-15 12:37:48',
                'updated_at' => '2020-01-15 12:38:02',
            ),
            8 => 
            array (
                'id' => 9,
                'item1' => 'region',
                'item2' => 'SBH',
                'item3' => 'Sabah',
                'item4' => NULL,
                'item5' => NULL,
                'item6' => NULL,
                'item7' => NULL,
                'item8' => NULL,
                'item9' => NULL,
                'item10' => NULL,
                'start_date' => NULL,
                'end_date' => NULL,
                'created_by' => 53062,
                'created_at' => '2020-01-15 12:37:49',
                'updated_at' => '2020-01-15 12:38:03',
            ),
            9 => 
            array (
                'id' => 10,
                'item1' => 'region',
                'item2' => 'SWK',
                'item3' => 'Sarawak',
                'item4' => NULL,
                'item5' => NULL,
                'item6' => NULL,
                'item7' => NULL,
                'item8' => NULL,
                'item9' => NULL,
                'item10' => NULL,
                'start_date' => NULL,
                'end_date' => NULL,
                'created_by' => 53062,
                'created_at' => '2020-01-15 12:37:51',
                'updated_at' => '2020-01-15 12:38:05',
            ),
            10 => 
            array (
                'id' => 11,
                'item1' => 'ot_status',
                'item2' => 'Assign',
                'item3' => 'Assign',
                'item4' => NULL,
                'item5' => NULL,
                'item6' => NULL,
                'item7' => NULL,
                'item8' => NULL,
                'item9' => NULL,
                'item10' => NULL,
                'start_date' => NULL,
                'end_date' => NULL,
                'created_by' => 53062,
                'created_at' => '2020-05-04 14:38:51',
                'updated_at' => '2020-05-04 14:38:52',
            ),
        ));
        
        
    }
}