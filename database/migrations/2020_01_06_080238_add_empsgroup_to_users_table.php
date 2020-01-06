<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmpsgroupToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
          $table->string('empsgroup',20)->after('state_id')->nullable();
          $table->string('empgroup',50)->after('empsgroup')->nullable();
          $table->integer('empstats')->after('reptto')->nullable();


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
        Schema::table('users', function (Blueprint $table) {
          $table->dropColumn('empsgroup');
          $table->dropColumn('empgroup');
          $table->dropColumn('empstats');
            //
        });
    }
}
