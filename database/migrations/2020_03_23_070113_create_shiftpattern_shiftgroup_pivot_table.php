<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShiftpatternShiftgroupPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_group_shift_pattern', function (Blueprint $table) {
            $table->integer('shift_group_id')->unsigned()->index();
            $table->foreign('shift_group_id', 'sgsp_sgrp_id')->references('id')->on('shift_groups')->onDelete('cascade');
            $table->smallInteger('shift_pattern_id')->unsigned()->index();
            $table->foreign('shift_pattern_id', 'sgsp_sptn_id')->references('id')->on('shift_patterns')->onDelete('cascade');
            $table->primary(['shift_group_id', 'shift_pattern_id'], 'sgsp_primary_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shift_group_shift_pattern');
    }
}
