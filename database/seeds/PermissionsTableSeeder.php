<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => '1-nav-menu-admin',
                'descr' => 'Admin navigation menu',
                'created_at' => '2019-08-27 09:37:29',
                'updated_at' => '2019-08-27 09:37:29',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'title' => '1-nav-menu-user',
                'descr' => 'User navigation menu',
                'created_at' => '2019-08-27 09:37:29',
                'updated_at' => '2019-08-27 09:37:29',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'title' => '1-nav-menu-ot',
                'descr' => 'Overtime navigation menu',
                'created_at' => '2019-08-27 09:37:29',
                'updated_at' => '2019-08-27 09:37:29',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'title' => '1-nav-menu-rpt',
                'descr' => 'Reports navigation menu',
                'created_at' => '2019-08-27 09:37:29',
                'updated_at' => '2019-08-27 09:37:29',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'title' => '1-nav-menu-shiftp',
                'descr' => 'Shift navigation menu',
                'created_at' => '2020-03-16 04:07:53',
                'updated_at' => '2020-03-16 04:07:53',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'title' => '1-nav-menu-cfg',
                'descr' => 'Config navigation menu',
                'created_at' => '2020-03-16 04:08:46',
                'updated_at' => '2020-03-16 04:08:46',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'title' => '2-cfg-role',
                'descr' => 'Config role',
                'created_at' => '2020-03-16 04:13:22',
                'updated_at' => '2020-03-16 04:13:22',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'title' => '2-cfg-company',
                'descr' => 'Config company',
                'created_at' => '2020-03-16 04:13:48',
                'updated_at' => '2020-03-16 04:13:48',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'title' => '2-cfg-state',
                'descr' => 'Config state',
                'created_at' => '2020-03-16 04:14:48',
                'updated_at' => '2020-03-16 04:14:48',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'title' => '2-cfg-holiday',
                'descr' => 'Config holiday',
                'created_at' => '2020-03-16 04:14:58',
                'updated_at' => '2020-03-16 04:14:58',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'title' => '2-cfg-psubarea',
                'descr' => 'Config psubarea',
                'created_at' => '2020-03-16 04:15:08',
                'updated_at' => '2020-03-16 04:15:08',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'title' => '2-cfg-eligibility',
                'descr' => 'Config eligibility',
                'created_at' => '2020-03-16 04:15:36',
                'updated_at' => '2020-03-16 04:15:36',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'title' => '2-cfg-wdt',
                'descr' => 'Config work day type',
                'created_at' => '2020-03-16 04:15:50',
                'updated_at' => '2020-03-16 04:15:50',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'title' => '2-cfg-shifttemplate',
                'descr' => 'Config shift template',
                'created_at' => '2020-03-16 04:16:15',
                'updated_at' => '2020-03-16 04:16:15',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'title' => '2-cfg-payroll-grp',
                'descr' => 'Config payroll grouping',
                'created_at' => '2020-03-16 04:16:34',
                'updated_at' => '2020-03-16 04:16:34',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'title' => '2-cfg-pay-sched',
                'descr' => 'Config payment schedule',
                'created_at' => '2020-03-16 04:16:48',
                'updated_at' => '2020-03-16 04:16:48',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'title' => '2-cfg-user-auth',
                'descr' => 'Config user authorization',
                'created_at' => '2020-03-16 04:17:26',
                'updated_at' => '2020-03-16 04:17:26',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'title' => '3-user-manual-claim-appr',
                'descr' => 'Manual claim approval',
                'created_at' => '2020-03-16 04:18:24',
                'updated_at' => '2020-03-16 04:18:24',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'title' => '3-user-logs',
                'descr' => 'User logs',
                'created_at' => '2020-03-16 04:18:47',
                'updated_at' => '2020-03-16 04:18:47',
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'title' => '3-user-profile',
                'descr' => 'User profiles',
                'created_at' => '2020-03-16 04:19:04',
                'updated_at' => '2020-03-16 04:19:04',
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'title' => '3-user-staff-list',
                'descr' => 'List of staff',
                'created_at' => '2020-03-16 04:19:31',
                'updated_at' => '2020-03-16 04:19:31',
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'title' => '4-ot-list-clockins',
                'descr' => 'List of Start/End OT',
                'created_at' => '2020-03-16 04:22:19',
                'updated_at' => '2020-03-16 04:22:19',
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'title' => '4-ot-apply',
                'descr' => 'Apply new OT',
                'created_at' => '2020-03-16 04:22:40',
                'updated_at' => '2020-03-16 04:22:40',
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'title' => '4-ot-claim-list',
                'descr' => 'List OT claim',
                'created_at' => '2020-03-16 04:23:37',
                'updated_at' => '2020-03-16 04:23:37',
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'title' => '4-ot-claim-pending-verify',
                'descr' => 'Pending Verification claims',
                'created_at' => '2020-03-16 04:24:15',
                'updated_at' => '2020-03-16 04:24:15',
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'title' => '4-ot-claim-pending-approve',
                'descr' => 'Pending Approval claims',
                'created_at' => '2020-03-16 04:25:15',
                'updated_at' => '2020-03-16 04:25:15',
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'title' => '4-ot-claim-verify-report',
                'descr' => 'Claim verification report',
                'created_at' => '2020-03-16 04:25:47',
                'updated_at' => '2020-03-16 04:25:47',
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'title' => '4-ot-claim-approve-report',
                'descr' => 'Claim approval report',
                'created_at' => '2020-03-16 04:26:08',
                'updated_at' => '2020-03-16 04:26:08',
                'deleted_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'title' => '5-shift-view-sched',
                'descr' => 'My shift schedule',
                'created_at' => '2020-03-16 04:30:20',
                'updated_at' => '2020-03-16 04:30:20',
                'deleted_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'title' => '4-ot-set-default-verifier',
                'descr' => 'Set default OT verifier',
                'created_at' => '2020-03-16 04:29:30',
                'updated_at' => '2020-03-16 04:29:30',
                'deleted_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'title' => '5-shift-group',
                'descr' => 'Shift Groups',
                'created_at' => '2020-03-16 04:32:04',
                'updated_at' => '2020-03-16 04:32:04',
                'deleted_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'title' => '6-rpt-ot-details',
                'descr' => 'Report - OT Details',
                'created_at' => '2020-03-16 04:34:21',
                'updated_at' => '2020-03-16 04:34:21',
                'deleted_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'title' => '6-rpt-ot-se',
                'descr' => 'Report - OT Start/End',
                'created_at' => '2020-03-16 04:36:39',
                'updated_at' => '2020-03-16 04:36:39',
                'deleted_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'title' => '6-rpt-ot-claim-summary',
                'descr' => 'Report - OT claim summary',
                'created_at' => '2020-03-16 04:37:11',
                'updated_at' => '2020-03-16 04:37:11',
                'deleted_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'title' => '6-rpt-ot-log-changes',
                'descr' => 'Report - OT log changes',
                'created_at' => '2020-03-16 04:37:30',
                'updated_at' => '2020-03-16 04:37:30',
                'deleted_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'title' => '5-shift-calendar',
                'descr' => 'Shift schedule',
                'created_at' => '2020-03-16 11:18:56',
                'updated_at' => '2020-03-16 11:18:56',
                'deleted_at' => NULL,
            ),
            36 => 
            array (
                'id' => 37,
                'title' => '5-shift-plan',
                'descr' => 'Plan Shift',
                'created_at' => '2020-03-16 11:19:15',
                'updated_at' => '2020-03-16 11:19:15',
                'deleted_at' => NULL,
            ),
            37 => 
            array (
                'id' => 38,
                'title' => '6-rpt-ot',
                'descr' => 'Report - OT',
                'created_at' => '2020-04-10 11:36:49',
                'updated_at' => '2020-04-10 11:36:49',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}