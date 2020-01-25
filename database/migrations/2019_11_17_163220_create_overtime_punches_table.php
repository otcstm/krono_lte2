<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOvertimePunchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtime_punches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('user_id');
            $table->integer('punch_id');
            $table->integer('parent_punch');
            $table->date('date');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->integer('hour')->default(0);
            $table->integer('minute')->default(0);
            $table->decimal('in_latitude', 9, 6)->default(0.0);
            $table->decimal('in_longitude', 9, 6)->default(0.0);
            $table->decimal('out_latitude', 9, 6)->default(0.0);
            $table->decimal('out_longitude', 9, 6)->default(0.0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overtime_punches');
    }
}
