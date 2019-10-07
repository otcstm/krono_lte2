<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftPatternDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_pattern_days', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->smallInteger('shift_pattern_id');
            $table->tinyInteger('day_seq');
            $table->smallInteger('day_type_id');
            // $table->tinyInteger('total_minutes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shift_pattern_days');
    }
}
