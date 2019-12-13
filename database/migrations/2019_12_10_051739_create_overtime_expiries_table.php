<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOvertimeExpiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtime_expiries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('company_id');
            $table->string('region', 25);
            $table->string('status');
            $table->string('active');
            $table->integer('noofmonth');
            $table->integer('noofdays');
            $table->string('based_date');
            $table->string('action_after');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overtime_expiries');
    }
}
