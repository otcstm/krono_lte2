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
                'created_at' => '2020-01-15 10:00:15',
                'updated_at' => '2020-01-15 10:00:15',
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
                'created_at' => '2020-01-15 10:00:15',
                'updated_at' => '2020-01-15 10:00:15',
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
                'created_at' => '2020-01-15 10:00:15',
                'updated_at' => '2020-01-15 10:00:15',
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
                'created_at' => '2020-01-15 10:00:15',
                'updated_at' => '2020-01-15 10:00:15',
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
                'created_at' => '2020-01-15 10:00:15',
                'updated_at' => '2020-01-15 10:00:15',
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
                'created_at' => '2020-01-15 10:00:15',
                'updated_at' => '2020-01-15 10:00:15',
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
                'created_at' => '2020-01-15 10:00:15',
                'updated_at' => '2020-01-15 10:00:15',
            ),
        ));


    }
}
