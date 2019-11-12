<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSalaryToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      DB::statement("ALTER TABLE users
        ADD COLUMN ot_salary_exception VARCHAR(1) AFTER reptto,
        ADD COLUMN ot_hour_exception VARCHAR(1) AFTER ot_salary_exception,
        ADD COLUMN salary DECIMAL(10,2) AFTER ot_hour_exception");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
          $table->dropColumn('ot_salary_exception');
          $table->dropColumn('ot_hour_exception');
          $table->dropColumn('salary');
        });
    }
}
