<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvalidEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invalid_emails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('refno')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('user_email')->nullable();
            $table->integer('verifier_id')->nullable();
            $table->string('verifier_email')->nullable();
            $table->integer('approver_id')->nullable();
            $table->string('approver_email')->nullable();
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
        Schema::dropIfExists('invalid_emails');
    }
}
