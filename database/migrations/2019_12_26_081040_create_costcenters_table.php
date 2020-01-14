<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCostcentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('costcenters', function (Blueprint $table) {
            $table->string('id',100)->primary('id');
            $table->string('descr', 100)->nullable();
            $table->string('company_id')->nullable();
            $table->integer('is_exist')->nullable();



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
        Schema::dropIfExists('costcenters');
    }
}
