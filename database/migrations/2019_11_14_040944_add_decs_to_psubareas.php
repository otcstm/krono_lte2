<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDecsToPsubareas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('psubareas', function (Blueprint $table) {
          $table->string('persareadesc', 50)->after('persarea');
          $table->string('perssubareades', 50)->after('perssubarea');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psubareas', function (Blueprint $table) {
          $table->dropColumn('persareadesc');
          $table->dropColumn('perssubareades');
        });
    }
}
