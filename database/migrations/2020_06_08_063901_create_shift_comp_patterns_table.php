<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftCompPatternsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_comp_patterns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('comp_id',20);
            $table->foreign('comp_id')->references('id')->on('companies');
            $table->smallInteger('shift_pattern_id')->unsigned();
            $table->foreign('shift_pattern_id')->references('id')->on('shift_patterns');
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('shift_comp_patterns');
    }
}
