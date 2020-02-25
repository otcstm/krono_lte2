<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserShiftPatternsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_shift_patterns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('shift_pattern_id');
            $table->string('sap_code', 20);
            $table->integer('created_by');
            $table->string('source','5');
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
        Schema::dropIfExists('user_shift_patterns');
    }
}
