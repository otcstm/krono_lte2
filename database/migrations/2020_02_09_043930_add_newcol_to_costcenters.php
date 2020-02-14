<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewcolToCostcenters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('costcenters', function (Blueprint $table) {
          $table->string('status', 2)->after('descr')->nullable();
          $table->string('costcenter_name', 40)->after('status')->nullable();
          $table->string('company_descr', 100)->after('company_id')->nullable();
          $table->string('replacement_cc', 40)->after('company_descr')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('costcenters', function (Blueprint $table) {
          $table->dropColumn('status');
          $table->dropColumn('costcenter_name');
          $table->dropColumn('company_descr');
          $table->dropColumn('replacement_cc');
        });
    }
}
