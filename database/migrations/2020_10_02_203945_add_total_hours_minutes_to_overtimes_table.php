<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalHoursMinutesToOvertimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('overtimes', function (Blueprint $table) {
            $table->decimal('total_hours_minutes', 10,2)->after('total_minute')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('overtimes', function (Blueprint $table) {
            $table->dropColumn('total_hours_minutes');
        });
    }
}
