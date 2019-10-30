<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftPlanStaffDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_plan_staff_days', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedBigInteger('shift_plan_id');
            $table->bigInteger('shift_plan_staff_id');
            $table->bigInteger('shift_plan_staff_template_id');
            $table->integer('user_id');
            $table->smallInteger('day_type_id');
            $table->date('work_date');
            $table->boolean('is_off_day')->default(false);
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();

            $table->foreign('shift_plan_id')
              ->references('id')
              ->on('shift_plans')
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
        Schema::dropIfExists('shift_plan_staff_days');
    }
}
