<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectToOvertimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('overtimes', function (Blueprint $table) {
          $table->string('costcenter',100)->nullable();
          $table->string('other_costcenter',100)->nullable();
          $table->string('project_type', 10)->nullable();
          $table->string('project_no',40)->nullable();
          $table->string('network_header', 20)->nullable();
          $table->string('network_act_no',10)->nullable();
          $table->string('legacy_code')->after('wage_type')->nullable();
          $table->string('order_no')->nullable();

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

      });
    }
}
