<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffPunchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_punches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('user_id');
            $table->datetime('punch_in_time');
            $table->decimal('in_latitude', 9, 6)->default(0.0);
            $table->decimal('in_longitude', 9, 6)->default(0.0);
            $table->datetime('punch_out_time')->nullable();
            $table->decimal('out_latitude', 9, 6)->default(0.0);
            $table->decimal('out_longitude', 9, 6)->default(0.0);
            $table->string('status', 10)->default('in');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_punches');
    }
}
