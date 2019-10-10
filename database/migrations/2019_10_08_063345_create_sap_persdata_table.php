<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSapPersdataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sap_persdata', function (Blueprint $table) {
            $table->integer('persno')->primary('persno')->unsigned();
            $table->string('nic',25);
            $table->string('oic',20)->nullable();
            $table->string('staffno',20);
            $table->string('complete_name',200);
            $table->integer('orgunit');
            $table->string('comp',10)->nullable();
            $table->string('persarea',10)->nullable();
            $table->string('perssubarea',10)->nullable();
            $table->string('empsgroup',20)->nullable();
            $table->string('empgroup',50)->nullable();
            $table->string('psgroup',10)->nullable();
            $table->string('pslvl',10)->nullable();
            $table->date('birthdate')->nullable();
            $table->string('email',200)->nullable();
            $table->string('cellno',100)->nullable();
            $table->integer('reptto')->nullable();
            $table->integer('empstats')->nullable();
            $table->integer('position')->nullable();
            $table->string('costcentr',20)->nullable();
            $table->date('upd_sap',20);
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
        Schema::dropIfExists('sap_persdata');
    }
}
