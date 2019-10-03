<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDayTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('code', 5);
            $table->string('description');
            $table->boolean('is_work_day');
            $table->time('start_time')->nullable();
            $table->tinyInteger('dur_hour')->default(0);
            $table->tinyInteger('dur_minute')->default(0);
            $table->smallInteger('total_minute')->default(0);
            $table->integer('created_by');
            $table->integer('last_edited_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('day_types');
    }
}
