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
                'id' => 2,
                'payrollgroup_id' => 1,
                'last_sub_date' => '2020-02-29',
                'last_approval_date' => '2020-03-03',
                'interface_date' => '2020-03-05',
                'payment_date' => '2020-03-25',
                'source' => 'OT',
                'created_by' => 19562,
                'updated_by' => 43570,
                'created_at' => '2020-03-17 10:52:19',
                'updated_at' => '2020-05-06 07:27:10',
            ),
            1 => 
            array (
                'id' => 4,
                'payrollgroup_id' => 1,
                'last_sub_date' => '2020-04-29',
                'last_approval_date' => '2020-04-30',
                'interface_date' => '2020-05-03',
                'payment_date' => '2020-05-15',
                'source' => 'OT',
                'created_by' => 43570,
                'updated_by' => 43570,
                'created_at' => '2020-04-02 10:52:10',
                'updated_at' => '2020-05-06 07:24:02',
            ),
            2 => 
            array (
                'id' => 5,
                'payrollgroup_id' => 1,
                'last_sub_date' => '2020-05-30',
                'last_approval_date' => '2020-06-03',
                'interface_date' => '2020-06-05',
                'payment_date' => '2020-06-25',
                'source' => 'OT',
                'created_by' => 43570,
                'updated_by' => 43570,
                'created_at' => '2020-04-03 12:25:13',
                'updated_at' => '2020-05-06 07:25:12',
            ),
            3 => 
            array (
                'id' => 7,
                'payrollgroup_id' => 1,
                'last_sub_date' => '2020-07-01',
                'last_approval_date' => '2020-07-03',
                'interface_date' => '2020-07-15',
                'payment_date' => '2020-07-27',
                'source' => 'OT',
                'created_by' => 43570,
                'updated_by' => NULL,
                'created_at' => '2020-04-06 13:19:26',
                'updated_at' => '2020-04-06 13:19:26',
            ),
            4 => 
            array (
                'id' => 8,
                'payrollgroup_id' => 1,
                'last_sub_date' => '2020-08-01',
                'last_approval_date' => '2020-08-05',
                'interface_date' => '2020-08-07',
                'payment_date' => '2020-08-27',
                'source' => 'OT',
                'created_by' => 43570,
                'updated_by' => NULL,
                'created_at' => '2020-04-06 13:45:13',
                'updated_at' => '2020-04-06 13:45:13',
            ),
            5 => 
            array (
                'id' => 9,
                'payrollgroup_id' => 1,
                'last_sub_date' => '2020-03-31',
                'last_approval_date' => '2020-04-03',
                'interface_date' => '2020-04-05',
                'payment_date' => '2020-04-24',
                'source' => 'OT',
                'created_by' => 43570,
                'updated_by' => 43570,
                'created_at' => '2020-04-07 09:37:55',
                'updated_at' => '2020-05-06 07:28:37',
            ),
            6 => 
            array (
                'id' => 10,
                'payrollgroup_id' => 1,
                'last_sub_date' => '2020-09-01',
                'last_approval_date' => '2020-09-02',
                'interface_date' => '2020-09-06',
                'payment_date' => '2020-09-29',
                'source' => 'OT',
                'created_by' => 43570,
                'updated_by' => 39868,
                'created_at' => '2020-04-07 18:08:11',
                'updated_at' => '2020-05-14 23:36:13',
            ),
            7 => 
            array (
                'id' => 12,
                'payrollgroup_id' => 1,
                'last_sub_date' => '2020-01-31',
                'last_approval_date' => '2020-02-03',
                'interface_date' => '2020-02-05',
                'payment_date' => '2020-02-25',
                'source' => 'OT',
                'created_by' => 43570,
                'updated_by' => 43570,
                'created_at' => '2020-04-14 10:58:00',
                'updated_at' => '2020-05-06 07:29:31',
            ),
            8 => 
            array (
                'id' => 13,
                'payrollgroup_id' => 1,
                'last_sub_date' => '2020-11-01',
                'last_approval_date' => '2020-11-03',
                'interface_date' => '2020-11-07',
                'payment_date' => '2020-11-27',
                'source' => 'OT',
                'created_by' => 43570,
                'updated_by' => NULL,
                'created_at' => '2020-04-15 14:03:39',
                'updated_at' => '2020-04-15 14:03:39',
            ),
            9 => 
            array (
                'id' => 14,
                'payrollgroup_id' => 1,
                'last_sub_date' => '2019-12-31',
                'last_approval_date' => '2020-01-01',
                'interface_date' => '2020-01-05',
                'payment_date' => '2020-01-17',
                'source' => 'OT',
                'created_by' => 43570,
                'updated_by' => NULL,
                'created_at' => '2020-05-06 07:32:02',
                'updated_at' => '2020-05-06 07:32:02',
            ),
        ));
        
        
    }
}