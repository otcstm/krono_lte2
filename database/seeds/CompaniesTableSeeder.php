<?php

use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('companies')->delete();
        
        \DB::table('companies')->insert(array (
            0 => 
            array (
                'id' => '1000',
                'company_descr' => 'Telekom Malaysia Berhad',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            1 => 
            array (
                'id' => '1001',
                'company_descr' => 'TM Wholesale',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            2 => 
            array (
                'id' => '1002',
                'company_descr' => 'TM Retail',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            3 => 
            array (
                'id' => '1003',
                'company_descr' => 'TM Corporate Center',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            4 => 
            array (
                'id' => '1010',
                'company_descr' => 'GITN Sdn. Berhad',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            5 => 
            array (
                'id' => '1020',
                'company_descr' => 'Menara KL Sdn. Bhd.',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            6 => 
            array (
                'id' => '1030',
                'company_descr' => 'TM Applied Business SB',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            7 => 
            array (
                'id' => '1040',
                'company_descr' => 'TM Info-Media Sdn Bhd',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            8 => 
            array (
                'id' => '1050',
                'company_descr' => 'TM R&D SDN. BHD.',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            9 => 
            array (
                'id' => '1060',
                'company_descr' => 'TSS Sdn. Bhd.',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            10 => 
            array (
                'id' => '1070',
                'company_descr' => 'TM Facilities Sdn Bhd',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            11 => 
            array (
                'id' => '1071',
                'company_descr' => 'TMF Autolease Sdn Bhd',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            12 => 
            array (
                'id' => '1072',
                'company_descr' => 'TMF Services Sdn Bhd',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            13 => 
            array (
                'id' => '1080',
                'company_descr' => 'TM Net Sdn. Bhd.',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            14 => 
            array (
                'id' => '1090',
                'company_descr' => 'TM Payphone Sdn. Bhd.',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            15 => 
            array (
                'id' => '1141',
                'company_descr' => 'Webe Digital Sdn Bhd',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            16 => 
            array (
                'id' => '1240',
            'company_descr' => 'Telekom Malaysia (HK) Ltd',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            17 => 
            array (
                'id' => '1250',
            'company_descr' => 'Telekom Malaysia(S) P Ltd',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            18 => 
            array (
                'id' => '1260',
            'company_descr' => 'Telekom Malaysia (UK) Ltd',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            19 => 
            array (
                'id' => '1270',
            'company_descr' => 'Telekom Malaysia (US) Inc',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            20 => 
            array (
                'id' => '201',
                'company_descr' => 'Axiata Group Berhad',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            21 => 
            array (
                'id' => '3001',
                'company_descr' => 'VADS Berhad',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            22 => 
            array (
                'id' => '3002',
                'company_descr' => 'VADS e-Services Sdn Bhd',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            23 => 
            array (
                'id' => '3004',
                'company_descr' => 'VADS Solution',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            24 => 
            array (
                'id' => '3005',
                'company_descr' => 'VADS Business Process',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            25 => 
            array (
                'id' => '3006',
                'company_descr' => 'Meganet Communication',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            26 => 
            array (
                'id' => '3007',
                'company_descr' => 'PT. VADS Indonesia',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            27 => 
            array (
                'id' => '3201',
                'company_descr' => 'Multimedia University',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
            28 => 
            array (
                'id' => 'ESOS',
                'company_descr' => 'NON SAP',
                'source' => 'OT',
                'updated_by' => '19562',
                'created_by' => '19562',
                'created_at' => '2019-10-25 07:46:16',
                'updated_at' => '2019-10-25 07:46:16',
            ),
        ));
        
        
    }
}