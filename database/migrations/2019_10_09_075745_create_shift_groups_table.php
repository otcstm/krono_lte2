<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('manager_id');
            $table->integer('planner_id');
            $table->string('group_name');
            $table->string('group_code', 20);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shift_groups');
    }
}
