<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetupCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setup_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('item1');
            $table->string('item2')->nullable();
            $table->string('item3')->nullable();
            $table->string('item4')->nullable();
            $table->string('item5')->nullable();
            $table->string('item6')->nullable();
            $table->string('item7')->nullable();
            $table->string('item8')->nullable();
            $table->string('item9')->nullable();
            $table->string('item10')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('created_by');
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
        Schema::dropIfExists('setup_codes');
    }
}
