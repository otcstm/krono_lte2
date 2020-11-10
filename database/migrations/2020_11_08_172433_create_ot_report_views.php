<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtReportViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement("
    CREATE 
    VIEW `v_ot_rpt1` AS
    SELECT 
        `ot`.`id` AS `id`,
        `ot`.`created_at` AS `created_at`,
        `ot`.`updated_at` AS `updated_at`,
        `ot`.`refno` AS `refno`,
        `ot`.`user_id` AS `user_id`,
        `ot`.`month_id` AS `month_id`,
        `ot`.`date` AS `date`,
        `ot`.`date_created` AS `date_created`,
        `ot`.`date_expiry` AS `date_expiry`,
        `ot`.`total_hour` AS `total_hour`,
        `ot`.`total_minute` AS `total_minute`,
        `ot`.`total_hours_minutes` AS `total_hours_minutes`,
        `ot`.`amount` AS `amount`,
        `ot`.`profile_id` AS `profile_id`,
        `ot`.`status` AS `status`,
        `ot`.`company_id` AS `company_id`,
        `ot`.`company_id_user` AS `company_id_user`,
        `ot`.`persarea` AS `persarea`,
        `ot`.`perssubarea` AS `perssubarea`,
        `ot`.`state_id` AS `state_id`,
        `ot`.`approver_id` AS `approver_id`,
        `ot`.`verifier_id` AS `verifier_id`,
        `ot`.`daytype_id` AS `daytype_id`,
        `ot`.`sal_exception` AS `sal_exception`,
        `ot`.`charge_type` AS `charge_type`,
        `ot`.`approved_date` AS `approved_date`,
        `ot`.`submitted_date` AS `submitted_date`,
        `ot`.`verification_date` AS `verification_date`,
        `ot`.`queried_date` AS `queried_date`,
        `ot`.`queried_id` AS `queried_id`,
        `ot`.`payment_date` AS `payment_date`,
        `ot`.`region` AS `region`,
        `ot`.`punch_id` AS `punch_id`,
        `ot`.`user_records_id` AS `user_records_id`,
        `ot`.`costcenter` AS `costcenter`,
        `ot`.`other_costcenter` AS `other_costcenter`,
        `ot`.`project_type` AS `project_type`,
        `ot`.`project_no` AS `project_no`,
        `ot`.`network_header` AS `network_header`,
        `ot`.`network_act_no` AS `network_act_no`,
        `ot`.`order_no` AS `order_no`,
        `ot`.`day_type_code` AS `day_type_code`,
        `ot`.`eligible_day` AS `eligible_day`,
        `ot`.`eligible_day_code` AS `eligible_day_code`,
        `ot`.`eligible_total_hours_minutes` AS `eligible_total_hours_minutes`,
        `ot`.`eligible_total_hours_minutes_code` AS `eligible_total_hours_minutes_code`,
        `ot`.`employee_type` AS `employee_type`,
        `ot`.`salary_exception` AS `salary_exception`,
        `u`.`name` AS `name`,
        `u`.`new_ic` AS `new_ic`,
        `u`.`staffno` AS `staffno`,
        `u`.`company_id` AS `u_company_id`,
        `u`.`empgroup` AS `empgroup`,
        `u`.`empsgroup` AS `empsgroup`,
        `u`.`empstats` AS `empstats`,
        `st`.`item3` AS `status_descr`
    FROM
        (((((`overtimes` `ot`
        LEFT JOIN `user_records` `u` ON (`u`.`user_id` = `ot`.`user_id`
            AND `u`.`upd_sap` = (SELECT 
                MAX(`u2`.`upd_sap`)
            FROM
                `user_records` `u2`
            WHERE
                `u2`.`user_id` = `ot`.`user_id`
                    AND `u2`.`upd_sap` <= `ot`.`date`)
            AND `u`.`id` = (SELECT 
                MAX(`u3`.`id`)
            FROM
                `user_records` `u3`
            WHERE
                `u3`.`user_id` = `u`.`user_id`
                    AND `u3`.`upd_sap` = `u`.`upd_sap`)))
        LEFT JOIN `salaries` `s` ON (`s`.`user_id` = `ot`.`user_id`
            AND `s`.`upd_sap` = (SELECT 
                MAX(`s2`.`upd_sap`)
            FROM
                `salaries` `s2`
            WHERE
                `s2`.`user_id` = `ot`.`user_id`
                    AND `s2`.`upd_sap` <= `ot`.`date`)
            AND `s`.`id` = (SELECT 
                MAX(`s3`.`id`)
            FROM
                `salaries` `s3`
            WHERE
                `s3`.`user_id` = `s`.`user_id`
                    AND `s3`.`upd_sap` = `s`.`upd_sap`)))
        LEFT JOIN `ot_indicators` `oti` ON (`oti`.`user_id` = `ot`.`user_id`
            AND `oti`.`upd_sap` = (SELECT 
                MAX(`oti2`.`upd_sap`)
            FROM
                `ot_indicators` `oti2`
            WHERE
                `oti2`.`user_id` = `ot`.`user_id`
                    AND `oti2`.`upd_sap` <= `ot`.`date`)
            AND `oti`.`id` = (SELECT 
                MAX(`oti3`.`id`)
            FROM
                `ot_indicators` `oti3`
            WHERE
                `oti3`.`user_id` = `oti`.`user_id`
                    AND `oti3`.`upd_sap` = `oti`.`upd_sap`)))
        LEFT JOIN `psubareas` `ps` ON (`ps`.`company_id` = `u`.`company_id`
            AND `ps`.`persarea` = `u`.`persarea`
            AND `ps`.`perssubarea` = `u`.`perssubarea`
            AND `ps`.`state_id` = `u`.`state_id`))
        LEFT JOIN `setup_codes` `st` ON (`st`.`item1` = 'ot_status'
            AND `st`.`item2` = `ot`.`status`))

      ");


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('v_ot_rpt1');
    }
}
