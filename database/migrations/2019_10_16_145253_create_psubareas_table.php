<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePsubareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psubareas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_id',10);
            $table->string('persarea', 25);
            $table->string('perssubarea', 25);
            $table->string('state_id', 25);
            $table->string('region', 25);
            $table->string('source',10)->nullable();
            $table->integer('created_by');
            $table->integer('last_edited_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psubareas');
    }
}
