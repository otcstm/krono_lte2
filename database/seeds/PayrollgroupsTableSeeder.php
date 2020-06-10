<?php

use Illuminate\Database\Seeder;

class PayrollgroupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payrollgroups')->delete();
        
        \DB::table('payrollgroups')->insert(array (
            0 => 
            array (
                'id' => 1,
                'pygroup' => 'A1',
                'created_by' => '19021',
                'updated_by' => NULL,
                'created_at' => '2020-03-12 14:53:32',
                'updated_at' => '2020-03-12 14:53:32',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 4,
                'pygroup' => 'U1',
                'created_by' => '43570',
                'updated_by' => NULL,
                'created_at' => '2020-04-13 12:02:38',
                'updated_at' => '2020-04-13 12:02:38',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}