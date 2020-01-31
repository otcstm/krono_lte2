<?php

use Illuminate\Database\Seeder;

class PaymentSchedulesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payment_schedules')->delete();
        
        \DB::table('payment_schedules')->insert(array (
            0 => 
            array (
                'id' => 1,
                'last_sub_date' => '2019-01-20',
                'last_approval_date' => '2019-01-21',
                'interface_date' => '2019-01-23',
                'payment_date' => '2019-02-26',
                'source' => 'OT',
                'created_by' => 43570,
                'updated_by' => 43570,
                'created_at' => '2019-11-22 10:14:49',
                'updated_at' => '2019-11-22 12:17:31',
            ),
            1 => 
            array (
                'id' => 2,
                'last_sub_date' => '2018-06-20',
                'last_approval_date' => '2018-06-21',
                'interface_date' => '2018-06-23',
                'payment_date' => '2019-07-25',
                'source' => 'OT',
                'created_by' => 43570,
                'updated_by' => 47360,
                'created_at' => '2019-11-22 10:20:14',
                'updated_at' => '2019-12-26 16:46:07',
            ),
            2 => 
            array (
                'id' => 3,
                'last_sub_date' => '2019-02-20',
                'last_approval_date' => '2019-02-21',
                'interface_date' => '2019-02-23',
                'payment_date' => '2019-03-26',
                'source' => 'OT',
                'created_by' => 43570,
                'updated_by' => NULL,
                'created_at' => '2019-11-22 11:17:18',
                'updated_at' => '2019-11-22 11:17:18',
            ),
            3 => 
            array (
                'id' => 4,
                'last_sub_date' => '2019-03-20',
                'last_approval_date' => '2019-03-21',
                'interface_date' => '2019-03-23',
                'payment_date' => '2019-04-26',
                'source' => 'OT',
                'created_by' => 43570,
                'updated_by' => NULL,
                'created_at' => '2019-11-22 12:18:44',
                'updated_at' => '2019-11-22 12:18:44',
            ),
            4 => 
            array (
                'id' => 5,
                'last_sub_date' => '2019-04-20',
                'last_approval_date' => '2019-04-21',
                'interface_date' => '2019-04-23',
                'payment_date' => '2019-05-24',
                'source' => 'OT',
                'created_by' => 43570,
                'updated_by' => NULL,
                'created_at' => '2019-11-22 12:19:59',
                'updated_at' => '2019-11-22 12:19:59',
            ),
            5 => 
            array (
                'id' => 6,
                'last_sub_date' => '2019-05-20',
                'last_approval_date' => '2019-05-21',
                'interface_date' => '2019-05-23',
                'payment_date' => '2019-06-26',
                'source' => 'OT',
                'created_by' => 43570,
                'updated_by' => NULL,
                'created_at' => '2019-11-22 15:26:55',
                'updated_at' => '2019-11-22 15:26:55',
            ),
            6 => 
            array (
                'id' => 7,
                'last_sub_date' => '2020-01-01',
                'last_approval_date' => '2020-01-02',
                'interface_date' => '2020-01-03',
                'payment_date' => '2020-01-04',
                'source' => 'OT',
                'created_by' => 47360,
                'updated_by' => NULL,
                'created_at' => '2020-01-09 16:52:41',
                'updated_at' => '2020-01-09 16:52:41',
            ),
        ));
        
        
    }
}