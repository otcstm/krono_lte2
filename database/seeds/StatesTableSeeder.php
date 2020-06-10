<?php

use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('states')->delete();
        
        \DB::table('states')->insert(array (
            0 => 
            array (
                'id' => 'JH',
            'state_descr' => 'Malaysia (Johor)',
                'source' => 'OT',
                'updated_by' => 43570,
                'created_by' => 19562,
                'created_at' => '2019-10-22 07:10:23',
                'updated_at' => '2020-04-09 13:53:54',
            ),
            1 => 
            array (
                'id' => 'KD',
            'state_descr' => 'Malaysia (Kedah Darul Aman)',
                'source' => 'OT',
                'updated_by' => 39868,
                'created_by' => 39868,
                'created_at' => '2020-05-15 12:37:59',
                'updated_at' => '2020-05-15 12:37:59',
            ),
            2 => 
            array (
                'id' => 'KH',
            'state_descr' => 'Malaysia (Kedah)',
                'source' => 'OT',
                'updated_by' => 19562,
                'created_by' => 19562,
                'created_at' => '2019-10-22 07:10:53',
                'updated_at' => '2019-10-22 07:10:53',
            ),
            3 => 
            array (
                'id' => 'KT',
            'state_descr' => 'Malaysia (Kelantan)',
                'source' => 'OT',
                'updated_by' => 19562,
                'created_by' => 19562,
                'created_at' => '2019-10-22 07:11:37',
                'updated_at' => '2019-10-22 07:11:37',
            ),
            4 => 
            array (
                'id' => 'LB',
            'state_descr' => 'Malaysia (Wilayah Persekutuan Labuan)',
                'source' => 'OT',
                'updated_by' => 19562,
                'created_by' => 19562,
                'created_at' => '2019-10-22 07:13:09',
                'updated_at' => '2019-10-22 07:13:09',
            ),
            5 => 
            array (
                'id' => 'ML',
            'state_descr' => 'Malaysia (Melaka)',
                'source' => 'OT',
                'updated_by' => 19562,
                'created_by' => 19562,
                'created_at' => '2019-10-22 07:10:06',
                'updated_at' => '2019-10-22 07:10:06',
            ),
            6 => 
            array (
                'id' => 'NS',
            'state_descr' => 'Malaysia (Negeri Sembilan)',
                'source' => 'OT',
                'updated_by' => 19562,
                'created_by' => 19562,
                'created_at' => '2019-10-22 07:09:55',
                'updated_at' => '2019-10-22 07:09:55',
            ),
            7 => 
            array (
                'id' => 'PG',
            'state_descr' => 'Malaysia (Pahang)',
                'source' => 'OT',
                'updated_by' => 19562,
                'created_by' => 19562,
                'created_at' => '2019-10-22 07:12:21',
                'updated_at' => '2019-10-22 07:12:21',
            ),
            8 => 
            array (
                'id' => 'PJ',
            'state_descr' => 'Malaysia (Wilayah Persekutuan Putrajaya)',
                'source' => 'OT',
                'updated_by' => 19562,
                'created_by' => 19562,
                'created_at' => '2019-10-22 07:08:09',
                'updated_at' => '2019-10-22 07:08:09',
            ),
            9 => 
            array (
                'id' => 'PK',
            'state_descr' => 'Malaysia (Perak)',
                'source' => 'OT',
                'updated_by' => 19562,
                'created_by' => 19562,
                'created_at' => '2019-10-22 07:11:24',
                'updated_at' => '2019-10-22 07:11:24',
            ),
            10 => 
            array (
                'id' => 'PP',
            'state_descr' => 'Malaysia (Pulau Pinang)',
                'source' => 'OT',
                'updated_by' => 19562,
                'created_by' => 19562,
                'created_at' => '2019-10-22 07:11:11',
                'updated_at' => '2019-10-22 07:11:11',
            ),
            11 => 
            array (
                'id' => 'PR',
            'state_descr' => 'Malaysia (Perlis)',
                'source' => 'OT',
                'updated_by' => 19562,
                'created_by' => 19562,
                'created_at' => '2019-10-22 07:10:36',
                'updated_at' => '2019-10-22 07:10:36',
            ),
            12 => 
            array (
                'id' => 'SB',
            'state_descr' => 'Malaysia (Sabah)',
                'source' => 'OT',
                'updated_by' => 19562,
                'created_by' => 19562,
                'created_at' => '2019-10-22 07:12:48',
                'updated_at' => '2019-10-22 07:12:48',
            ),
            13 => 
            array (
                'id' => 'SN',
            'state_descr' => 'Malaysia (Selangor)',
                'source' => 'OT',
                'updated_by' => 19562,
                'created_by' => 19562,
                'created_at' => '2019-10-22 07:08:28',
                'updated_at' => '2019-10-22 07:08:28',
            ),
            14 => 
            array (
                'id' => 'SR',
            'state_descr' => 'Malaysia (Sarawak)',
                'source' => 'OT',
                'updated_by' => 19562,
                'created_by' => 19562,
                'created_at' => '2019-10-22 07:13:29',
                'updated_at' => '2019-10-22 07:13:29',
            ),
            15 => 
            array (
                'id' => 'TG',
            'state_descr' => 'Malaysia (Terengganu)',
                'source' => 'OT',
                'updated_by' => 19562,
                'created_by' => 19562,
                'created_at' => '2019-10-22 07:12:07',
                'updated_at' => '2019-10-22 07:12:07',
            ),
            16 => 
            array (
                'id' => 'WP',
            'state_descr' => 'Malaysia (Wilayah Persekutuan K.Lumpur)',
                'source' => 'OT',
                'updated_by' => 19562,
                'created_by' => 19562,
                'created_at' => '2019-10-22 07:07:55',
                'updated_at' => '2019-10-22 07:07:55',
            ),
        ));
        
        
    }
}