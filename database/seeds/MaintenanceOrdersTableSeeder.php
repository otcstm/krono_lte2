<?php

use Illuminate\Database\Seeder;

class MaintenanceOrdersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('maintenance_orders')->delete();
        
        \DB::table('maintenance_orders')->insert(array (
            0 => 
            array (
                'id' => '100000682668',
                'descr' => 'CM/D.ROSAK/TMP_001/37-38/JLN PERDAGANGAN',
                'type' => 'ZTM1',
                'status' => 'REL',
                'cost_center' => 'WPJB1G',
                'company_code' => '1000',
                'approver_id' => '1157',
                'budget' => '0.00',
                'created_at' => '2020-02-09 00:00:00',
                'updated_at' => '2020-02-09 00:00:00',
            ),
            1 => 
            array (
                'id' => '100000682879',
                'descr' => 'CMDS/KA019/SISEMBER2019/BRAKS/DS,D1/IB10',
                'type' => 'ZTM1',
                'status' => 'REL',
                'cost_center' => 'WPBC1J',
                'company_code' => '1000',
                'approver_id' => '2028',
                'budget' => '0.00',
                'created_at' => '2020-02-09 00:00:00',
                'updated_at' => '2020-02-09 00:00:00',
            ),
            2 => 
            array (
                'id' => '100000683885',
                'descr' => 'CM/ENZ AS/KKN_004_0044/KERJA-KERJA GANTI',
                'type' => 'ZTM1',
                'status' => 'REL',
                'cost_center' => 'WPKA1E',
                'company_code' => '1000',
                'approver_id' => '23031',
                'budget' => '0.00',
                'created_at' => '2020-02-09 00:00:00',
                'updated_at' => '2020-02-09 00:00:00',
            ),
            3 => 
            array (
                'id' => '110001731213',
                'descr' => 'CHANGE MANHOLE COVER',
                'type' => 'ZTM2',
                'status' => 'REL',
                'cost_center' => 'WPBE1F',
                'company_code' => '1000',
                'approver_id' => '13995',
                'budget' => '18500.00',
                'created_at' => '2020-02-09 00:00:00',
                'updated_at' => '2020-02-09 00:00:00',
            ),
            4 => 
            array (
                'id' => '110001731259',
                'descr' => 'ZTM2/DN/MK-SANG/DN_C013/FOC_PM/KERJA-KER',
                'type' => 'ZTM2',
                'status' => 'REL',
                'cost_center' => 'WPDA1A',
                'company_code' => '1000',
                'approver_id' => '13363',
                'budget' => '7712.00',
                'created_at' => '2020-02-09 00:00:00',
                'updated_at' => '2020-02-09 00:00:00',
            ),
            5 => 
            array (
                'id' => '120100021534',
                'descr' => 'refurb AL5AC 0014/16',
                'type' => 'ZTM3',
                'status' => 'REL',
                'cost_center' => 'WPPQPP',
                'company_code' => '1000',
                'approver_id' => '1910',
                'budget' => '0.00',
                'created_at' => '2020-02-09 00:00:00',
                'updated_at' => '2020-02-09 00:00:00',
            ),
            6 => 
            array (
                'id' => '120100021572',
            'descr' => 'Baikpulih Rectifier NPR48 (KL)',
                'type' => 'ZTM3',
                'status' => 'REL',
                'cost_center' => 'WPWQKL',
                'company_code' => '1000',
                'approver_id' => '2092',
                'budget' => '0.00',
                'created_at' => '2020-02-09 00:00:00',
                'updated_at' => '2020-02-09 00:00:00',
            ),
            7 => 
            array (
                'id' => '130000104475',
                'descr' => 'RR-059328/ ZTM4/BO_VF5005_13/Ganti kabel',
                'type' => 'ZTM4',
                'status' => 'REL',
                'cost_center' => 'WPAA1E',
                'company_code' => '1000',
                'approver_id' => '2119',
                'budget' => '0.00',
                'created_at' => '2020-02-09 00:00:00',
                'updated_at' => '2020-02-09 00:00:00',
            ),
            8 => 
            array (
                'id' => '130000104517',
                'descr' => 'KABEL CURI SMD_007_34/RR-059503 ',
                'type' => 'ZTM4',
                'status' => 'REL',
                'cost_center' => 'WPJB1F',
                'company_code' => '1000',
                'approver_id' => '1742',
                'budget' => '0.00',
                'created_at' => '2020-02-09 00:00:00',
                'updated_at' => '2020-02-09 00:00:00',
            ),
            9 => 
            array (
                'id' => 'A2B121902930',
            'descr' => 'ZROA/SRG_F025/UG/RWO CASES(3RD PARTY)_CA',
                'type' => 'ZROA',
                'status' => 'REL',
                'cost_center' => 'WPBC1G',
                'company_code' => '1000',
                'approver_id' => '2028',
                'budget' => '70000.00',
                'created_at' => '2020-02-09 00:00:00',
                'updated_at' => '2020-02-09 00:00:00',
            ),
            10 => 
            array (
                'id' => 'A5T121902949',
                'descr' => 'ZROA/RR MJKH/KRA P  NYIREH /LORI LANGAR',
                'type' => 'ZROA',
                'status' => 'REL',
                'cost_center' => 'WPDTTD',
                'company_code' => '1000',
                'approver_id' => '13363',
                'budget' => '40000.00',
                'created_at' => '2020-02-09 00:00:00',
                'updated_at' => '2020-02-09 00:00:00',
            ),
        ));
        
        
    }
}