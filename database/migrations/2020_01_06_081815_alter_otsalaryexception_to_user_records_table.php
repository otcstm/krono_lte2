<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOtsalaryexceptionToUserRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_records', function (Blueprint $table) {
          //  $table->string('ot_salary_exception')->default('')->nullable(false)->change();
          //  $table->string('ot_hour_exception')->default('')->nullable(false)->change();
             $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_records', function (Blueprint $table) {
          $table->dropIndex(['user_id']);
            //
        });
    }
}
