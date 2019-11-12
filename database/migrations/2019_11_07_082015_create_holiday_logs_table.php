<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHolidayLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holiday_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('holiday_id');
            $table->string('descr', 255)->nullable();
            $table->date('dt');
            $table->integer('guarantee_flag');
            $table->string('states', 750)->nullable();
            $table->bigInteger('update_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('holiday_logs');
    }
}
