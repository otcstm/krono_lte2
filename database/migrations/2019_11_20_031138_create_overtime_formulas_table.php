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
          $table->string('company_id')->nullable();
          $table->string('region', 25);
          $table->string('legacy_codes')->nullable();
          $table->string('ot_salary_exception')->nullable();
          $table->string('day_type', 25);
          $table->string('wagetype')->nullable();
          $table->string('descr')->nullable();
          $table->string('claim_type')->nullable();
          $table->integer('min_hour');
          $table->integer('min_minute');
          $table->integer('max_hour');
          $table->integer('max_minute');
          $table->string('unit');
          $table->decimal('rate',4,2)->default(0.0);
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
