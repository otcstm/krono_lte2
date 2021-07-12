<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RmSalaryFromUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
          $table->dropColumn('ot_salary_exception');
          $table->dropColumn('ot_hour_exception');
          $table->dropColumn('salary');

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
          $table->string('ot_salary_exception', 1)->after('persarea')->nullable();
          $table->string('ot_hour_exception', 1)->after('perssubarea')->nullable();
          $table->decimal('salary',10,2)->default(0.00);

        });
    }
}
