<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOvertimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtimes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('refno')->unique();
            $table->integer('user_id');
            $table->date('date');
            $table->date('date_created');
            $table->date('date_expiry');
            $table->integer('total_hour');
            $table->integer('total_minute');
            $table->integer('profile_id')->nullable();
            $table->string('status')->nullable();
            $table->string('company_id')->nullable();
            $table->string('state_id')->nullable();
            $table->integer('approver_id')->nullable();
            $table->integer('verifier_id')->nullable();            
            $table->integer('daytype_id')->nullable();
            $table->string('justification')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overtimes');
    }
}
