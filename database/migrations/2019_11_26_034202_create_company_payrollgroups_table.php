<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyPayrollgroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_payrollgroups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('payrollgroup_id')->unsigned();
            $table->foreign('payrollgroup_id')->references('id')->on('payrollgroups')->onDelete('cascade');
            $table->string('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('company_payrollgroups');
    }
}
