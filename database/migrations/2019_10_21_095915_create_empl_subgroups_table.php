<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmplSubgroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empl_subgroups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_id',10)->nullable();
            $table->string('empgroup',50)->nullable();
            $table->string('empsgroup',20)->nullable();
            $table->string('psgroup',20)->nullable();
            $table->string('pslvl',10)->nullable();
            $table->string('empl_type',30)->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('empl_subgroups');
    }
}
