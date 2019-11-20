<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOvertimeFormulasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtime_formulas', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->bigInteger('compregconfig_id');
          $table->string('legacy_codes')->nullable();
          $table->string('day_type', 25);
          $table->string('wagetype')->nullable();
          $table->string('descr')->nullable();
          $table->integer('min_hour');
          $table->integer('max_hour');
          $table->string('unit');
          $table->decimal('rate',2,2);
          $table->date('start_date')->nullable();
          $table->date('end_date')->nullable();
          $table->integer('created_by');
          $table->integer('last_edited_by')->nullable();
          $table->integer('deleted_by')->nullable();
          $table->softDeletes();
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
        Schema::dropIfExists('overtime_formulas');
    }
}
