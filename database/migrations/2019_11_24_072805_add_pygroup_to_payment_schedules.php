<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPygroupToPaymentSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('payrollgroup_id')->nullable()->after('id');
            $table->foreign('payrollgroup_id')
              ->references('id')
              ->on('payrollgroups')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            Schema::table('payment_schedules', function ($table) {
                $table->dropForeign('payment_schedules_payrollgroup_id_foreign');
            });
        } catch (Exception $e) {}

        Schema::table('payment_schedules', function (Blueprint $table) {
            $table->dropColumn('payrollgroup_id');
        });
    }
}
