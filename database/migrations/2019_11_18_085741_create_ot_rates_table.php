<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOtRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ot_rates', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('company_id',10);
          $table->string('region', 25);
          $table->string('legacy_codes');
          $table->string('day_type', 25);
          $table->string('wagetype');
          $table->string('descr')->nullable();
          $table->integer('min_hour');
          $table->integer('max_hour');
          $table->string('unit');
          $table->integer('divbyhour');
          $table->integer('divbyday');
          $table->decimal('multiple_by',2,2);
          $table->decimal('salary_cap',10,2)->nullable();
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
        Schema::dropIfExists('ot_rates');
    }
}
