<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReminderJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminder_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('fetched_users_time')->nullable();
            $table->timestamp('complete_time')->nullable();
            $table->tinyInteger('week');
            $table->tinyInteger('year');
            $table->integer('expected_count')->default(0);
            $table->integer('processed_count')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reminder_jobs');
    }
}
