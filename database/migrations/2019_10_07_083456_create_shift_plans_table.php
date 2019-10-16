<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('creator_id');
            $table->timestamp('plan_month')->nullable();
            $table->string('name');
            // this department is the shift group
            $table->integer('department')->nullable();
            $table->string('status', 20);
            $table->integer('approver_id')->nullable();
            $table->timestamp('approved_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shift_plans');
    }
}
