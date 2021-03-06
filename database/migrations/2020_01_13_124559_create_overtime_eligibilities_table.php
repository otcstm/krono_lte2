<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOvertimeEligibilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtime_eligibilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_id');
            $table->string('region', 25);
            $table->decimal('salary_cap',10,2)->default(0.0);
            $table->integer('hourpermonth');
            $table->integer('hourperday');
            $table->integer('daypermonth');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('created_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overtime_eligibilities');
    }
}
