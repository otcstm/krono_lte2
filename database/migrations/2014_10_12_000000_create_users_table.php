<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            $table->integer('id')->primary('id')->unsigned();


            $table->string('name');
            $table->string('email');
            $table->rememberToken();

            $table->string('staff_no');
            $table->integer('persno')->nullable();
            $table->string('new_ic')->unique();
            $table->string('company_id')->nullable();
            $table->integer('orgunit')->nullable();
            $table->string('persarea',10)->nullable();
            $table->string('perssubarea',10)->nullable();
            $table->string('state_id')->nullable();
            $table->integer('reptto')->nullable();
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
        Schema::dropIfExists('users');
    }
}
