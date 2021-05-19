<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveLegacyCodesWageTypeTotalHoursMinutesFromOvertimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('overtimes', function (Blueprint $table) {
            $table->dropColumn('legacy_code');
            $table->dropColumn('wage_type');
            $table->dropColumn('total_hours_minutes');
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
            
            $table->string('wage_type')->nullable();
            $table->string('legacy_code')->after('wage_type')->nullable();
            $table->decimal('total_hours_minutes', 10,2)->default(0);
        });
    }
}
