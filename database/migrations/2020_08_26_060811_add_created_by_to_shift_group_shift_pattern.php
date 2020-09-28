<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedByToShiftGroupShiftPattern extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shift_group_shift_pattern', function (Blueprint $table) {
            //
            $table->integer('created_by')->default(0);
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
        Schema::table('shift_group_shift_pattern', function (Blueprint $table) {
            //
            $table->integer('created_by')->default(0);
            $table->timestamps();
        });
    }
}
