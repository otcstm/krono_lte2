<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdDmToUspTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_shift_patterns', function (Blueprint $table) {
            $table->string('upd_dm')->after('upd_sap')->nullable();
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_shift_patterns', function (Blueprint $table) {
            $table->dropColumn('upd_dm');
        });
    }
}
