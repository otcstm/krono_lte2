<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftPlanStaffTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_plan_staff_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->tinyInteger('day_seq');
            $table->bigInteger('shift_plan_staff_id');
            $table->smallInteger('shift_pattern_id');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shift_plan_staff_templates');
    }
}
